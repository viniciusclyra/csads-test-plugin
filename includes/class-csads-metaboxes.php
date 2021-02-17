<?php

/**
 * CS Ads
 *
 * @package Csads
 */

/**
 * Register metaboxes.
 *
 * @package Csads
 */
class Csads_Metaboxes
{

	public function init()
	{
		add_action('add_meta_boxes', array($this, 'csads_meta_boxes'));
		add_action('save_post', array($this, 'save_meta_boxes'),  10, 2);
		add_filter('enter_title_here', array($this, 'csads_title_text'));
	}

	/**
	 * Register the metaboxes to be used for the csad post type.
	 *
	 * @since 0.1.0
	 */
	public function csads_meta_boxes()
	{
		add_meta_box(
			'csads_fields',
			'Informações do anúncio',
			array($this, 'render_meta_boxes'),
			'csad',
			'advanced',
			'high'
		);
	}

	/**
	 * The HTML for the fields.
	 *
	 * @since 0.1.0
	 */
	public function render_meta_boxes($post)
	{

		$meta = get_post_custom($post->ID);
		$description = !isset($meta['csads-description'][0]) ? '' : $meta['csads-description'][0];

		wp_nonce_field(basename(__FILE__), 'csads_fields'); ?>

		<table class="form-table">
			<tr>
				<td>
					<label for="csads-description"><?php _e('Descrição', 'csads'); ?>
					</label>
				</td>
				<td>
					<textarea name="csads-description" style="width:100%;min-height:200px;" type="textarea" class="form-control" rows="5" required><?php echo $description; ?></textarea>
				</td>
			</tr>
		</table>

	<?php
	}

	/**
	 * Change editor title placeholder
	 *
	 * @param string $title Default title placeholder.
	 * @return string Changed title placeholder.
	 */
	public function csads_title_text($title)
	{
		$screen = get_current_screen();

		if ('csad' == $screen->post_type) {
			$title = 'Título do anúncio';
		}

		return $title;
	}



	/**
	 * Save metaboxes.
	 *
	 * @since 0.1.0
	 */
	public function save_meta_boxes($post_id)
	{

		global $post;

		// Verify nonce
		if (!isset($_POST['csads_fields']) || !wp_verify_nonce($_POST['csads_fields'], basename(__FILE__))) {
			return $post_id;
		}

		// Check Autosave
		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit'])) {
			return $post_id;
		}

		// Don't save if only a revision
		if (isset($post->post_type) && $post->post_type == 'revision') {
			return $post_id;
		}

		// Check permissions
		if (!current_user_can('edit_post', $post->ID)) {
			return $post_id;
		}

		$meta['csads-description'] = (isset($_POST['csads-description']) ? $_POST['csads-description'] : '');


		foreach ($meta as $key => $value) {
			update_post_meta($post->ID, $key, $value);
		}
	}
}

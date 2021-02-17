<?php

/**
 * CS Ads
 *
 * @package Csads
 */

/**
 * Register post types and taxonomies.
 *
 * @package Csads
 */
class Csads_Public
{

	public function init()
	{
		add_action('wp_enqueue_scripts', array($this, 'load_scripts_styles'));
		add_filter('the_content', array($this, 'csads_custom_content'));
		add_shortcode('csads-searchbar', array($this, 'render_searchbar_form'));
		add_shortcode('csads-posts', array($this, 'render_csads_posts'));
	}

	/**
	 * Load scripts and Styles.
	 *
	 * @return void
	 */
	public function load_scripts_styles()
	{
		wp_enqueue_style('csads-public-style', plugins_url('/assets/css/csads-public.css', plugin_dir_path(__FILE__)));
	}

	/**
	 * The HTML for the searchbar
	 *
	 * @return void
	 */
	public function render_searchbar_form()
	{
		$form = "
		<form role='search' method='get' id='searchform' action='" . home_url('/') . "' >
			<div>
				<label class='screen-reader-text' for='s'>" . __('Pesquisar: ') . "</label>
				<input type='text' value='" . get_search_query() . "' name='s' id='s' />
				<input type='submit' id='searchsubmit' value='" . esc_attr__('Search') . "' />
			</div>
		</form>";

		return $form;
	}

	/**
	 * The HTML for the list of csad posts.
	 *
	 * @return void
	 */
	public function render_csads_posts()
	{
		$args = array(
			'post_type' 	 => 'csad',
			'post_status' 	 => 'publish',
			'posts_per_page' => 8,
			'orderby' 		 => 'date',
			'order' 		 => 'DESC',
		);

		$results = new WP_Query($args);
		echo "<div class='csads-ads-container'>";

		while ($results->have_posts()) : $results->the_post();
			$post_id = get_the_ID();
			$post_title = get_the_title($post_id);
			$post_link = get_post_permalink($post_id);
			$post_thumb_url = get_the_post_thumbnail_url($post_id, array(80, 80));
			$post_meta = get_post_meta($post_id);
			$post_tags = get_the_terms($post_id, 'csad-tag');
			$html_tags = "<ul class='csads-ad-tags'>";

			foreach ($post_tags as $tag) {
				$html_tags .= "<li class='csads-ad-tag'><a href='" . get_term_link($tag) . "'>" . $tag->name . "</a></li>";
			}

			$html_tags .= "</ul>";

			$html_csad = "
			<div class='csads-ad'>
				<div class='csads-ad-img-container'>
					<img class='csads-ad-img' src='" . $post_thumb_url . "' height='60' width='60'>
				</div>
				<div class='csads-ad-content'>
					<h5 class='csads-ad-title'><a href='" . $post_link . "'>" . $post_title . "</a></h5>
					<p class='csads-ad-desc'>" . wp_trim_words($post_meta["csads-description"][0], 36) . "</p>
					" . $html_tags . "
				</div>
			</div>";

			echo $html_csad;

		endwhile;

		echo "<a href='?post_type=csad'>Veja aqui todos os anúncios...</a>";
		echo "</div>";

		wp_reset_postdata();
	}


	/**
	 * Add the description content to the post content page.
	 *
	 * @param string $content Default HTML content.
	 * @return string The modified HTML content. 
	 */
	public function csads_custom_content($content)
	{
		global $post;

		$post_meta = get_post_meta($post->ID);

		if (is_singular('csad') && in_the_loop()) {
			$content .= "<p>" . $post_meta["csads-description"][0] . "</p>";
		}
		return $content;
	}
}

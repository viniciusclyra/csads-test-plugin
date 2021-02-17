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
class Csads_Registrations
{

	public $post_type = 'csad';

	public $taxonomies = array('csad-tag');

	public function init()
	{
		// Add the csad post type and taxonomies
		add_action('init', array($this, 'register'));
	}

	/**
	 * Initiate registrations of post type and taxonomies.
	 *
	 * @uses Csads_Registrations::register_post_type()
	 * @uses Csads_Registrations::register_taxonomy_category()
	 */
	public function register()
	{
		$this->register_post_type();
		$this->register_taxonomy_category();
	}

	/**
	 * Register the custom post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	protected function register_post_type()
	{
		$labels = array(
			'name'               	=> __('Anúncios', 'csads'),
			'singular_name'      	=> __('Anúncio', 'csads'),
			'add_new'            	=> __('Novo anúncio', 'csads'),
			'add_new_item'       	=> __('Novo anúncio', 'csads'),
			'edit_item'          	=> __('Editar Anúncio', 'csads'),
			'new_item'           	=> __('Novo anúncio', 'csads'),
			'view_item'          	=> __('Ver anúncio', 'csads'),
			'search_items'       	=> __('Pesquisar anúncio', 'csads'),
			'featured_image' 	 	=> __('Imagem do anúncio', 'csads'),
			'set_featured_image' 	=> __('Selecionar imagem do anúncio', 'csads'),
			'remove_featured_image' => __('Remover imagem do anúncio', 'csads'),
			'use_featured_image' 	=> __('Usar imagem do anúncio', 'csads'),
			'not_found'          	=> __('Nenhum anúncio encontrado', 'csads'),
			'not_found_in_trash' 	=> __('Nenhum anúncio encontrado na lixeira', 'csads'),
		);

		$supports = array(
			'title',
			'thumbnail',
			'revisions',
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array('slug' => 'csad',),
			'menu_position'   => 30,
			'menu_icon'       => 'dashicons-megaphone',
		);

		$args = apply_filters('csad_post_type_args', $args);

		register_post_type($this->post_type, $args);
	}

	/**
	 * Register a taxonomy for Csad tags.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	protected function register_taxonomy_category()
	{
		$labels = array(
			'name'                       => __('Tags', 'csads'),
			'singular_name'              => __('Tag', 'csads'),
			'menu_name'                  => __('Tags', 'csads'),
			'edit_item'                  => __('Editar tag', 'csads'),
			'update_item'                => __('Alterar tag', 'csads'),
			'add_new_item'               => __('Nova tag', 'csads'),
			'new_item_name'              => __('Novo nome', 'csads'),
			'all_items'                  => __('Todas as tags', 'csads'),
			'search_items'               => __('Pesquisar tags', 'csads'),
			'popular_items'              => __('Tags populares', 'csads'),
			'add_or_remove_items'        => __('Adicionar ou remover tags', 'csads'),
			'choose_from_most_used'      => __('Tags mais utilizadas', 'csads'),
			'not_found'                  => __('Tags não encontradas', 'csads'),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => false,
			'rewrite'           => array('slug' => 'csads-tag'),
			'show_admin_column' => true,
			'query_var'         => true,
		);

		$args = apply_filters('csads_tag_args', $args);

		register_taxonomy($this->taxonomies[0], $this->post_type, $args);
	}
}

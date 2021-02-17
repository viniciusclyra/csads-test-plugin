<?php

/**
 * Coopersystem Ads
 * 
 * @wordpress-plugin
 * Plugin Name: Coopersystem Ads
 * Plugin URI:  https://coopersystem.com.br/
 * Description: Plugin para gerenciar e exibir os anúncios.
 * Version:     1.0.0
 * Author:		Vinícius Lyra
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

// Required files for registering the post type and taxonomies.
require plugin_dir_path(__FILE__) . 'includes/class-csads.php';
require plugin_dir_path(__FILE__) . 'includes/class-csads-registrations.php';
require plugin_dir_path(__FILE__) . 'includes/class-csads-metaboxes.php';

// Instantiate registration class, so we can add it as a dependency to main plugin class.
$post_type_registrations = new Csads_Registrations;

// Instantiate main plugin file, so activation callback does not need to be static.
$post_type = new Csads($post_type_registrations);

// Register callback that is fired when the plugin is activated.
register_activation_hook(__FILE__, array($post_type, 'activate'));

// Initialize registrations for post-activation requests.
$post_type_registrations->init();

// Initialize metaboxes
$post_type_metaboxes = new Csads_Metaboxes;
$post_type_metaboxes->init();


/**
 * Initialize admin and public instances
 */
if (is_admin()) {
	require plugin_dir_path(__FILE__) . 'includes/class-csads-admin.php';

	$post_type_admin = new Csads_Admin($post_type_registrations);
	$post_type_admin->init();
} else {
	require plugin_dir_path(__FILE__) . 'includes/class-csads-public.php';

	$post_type_admin = new Csads_Public();
	$post_type_admin->init();
}

<?php
/**
 * Plugin Name: Elemendas Addons
 * Description: Improving the search results archive page for Elementor.
 * Plugin URI:  https://elementor.com/
 * Text Domain: elemendas-addons
 * Version:     1.0.0
 * Elementor tested up to: 3.5.6
 * Elementor Pro tested up to: 3.6.3
 * Author:      Elemendas
 * Author URI:  https://elemendas.com/
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define('ELEMENDAS_ADDONS_VERSION', '1.0' );
define('ELM_PLUGIN_URL', trailingslashit(plugins_url('/', __FILE__)));


function elemendas_addons() {
	// Load plugin file
	require_once( __DIR__ . '/includes/plugin.php' );

	// Run the plugin
	\Elemendas_Addons\Plugin::instance();
}
add_action( 'plugins_loaded', 'elemendas_addons' );

<?php
/**
 * Plugin Name: Elemendas Addons
 * Description: Improving the search results archive page for Elementor.
 * Plugin URI:  https://elementor.com/
 * Text Domain: elemendas-addons
 * Version: 2.2.0
 * Elementor tested up to: 3.6.5
 * Elementor Pro tested up to: 3.6.5
 * Author:      Elemendas, Santiago Becerra
 * Author URI:  https://elemendas.com/
 * Requires at least and Requires PHP tags are declared at readme.txt and verified by a built-in function at /includes/plugin.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define('ELEMENDAS_ADDONS_VERSION', '2.2.0' );
define('ELM_PLUGIN_URL', trailingslashit(plugins_url('/', __FILE__)));


function elemendas_addons() {
	// Load plugin file
	require_once( __DIR__ . '/includes/plugin.php' );

	// Run the plugin
	\Elemendas_Addons\Plugin::instance();
}
add_action( 'plugins_loaded', 'elemendas_addons' );

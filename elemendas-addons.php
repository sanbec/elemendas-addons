<?php
/**
 * This Elementor Addon plugin improves the search results archive page and adds awesome widgets.
 *
 * @package   Elemendas_Addons
 * @author    Santiago Becerra <santi@wpcombo.com>
 * @license   GPL-3.0+
 * @link      https://elemendas.com
 * @copyright 2022 Santiago Becerra
 *
 * @wordpress-plugin
 * Plugin Name: Elemendas Addons
 * Description: Improving the search results archive page for Elementor.
 * Plugin URI:  https://elemendas.com/elemendas-addons/
 * Text Domain: elemendas-addons
 * Version: 2.2.4
 * Elementor tested up to: 3.6.5
 * Elementor Pro tested up to: 3.6.5
 * Author:      Elemendas, Santiago Becerra
 * Author URI:  https://elemendas.com/
 * Requires at least and Requires PHP tags are declared at readme.txt and verified by a built-in function at /includes/plugin.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define('ELEMENDAS_ADDONS_VERSION', '2.2.4' );
define('ELM_PLUGIN_URL', trailingslashit(plugins_url('/', __FILE__)));


function elemendas_addons() {
	// Load plugin file
	require_once( __DIR__ . '/includes/plugin.php' );

	// Run the plugin
	\Elemendas_Addons\Plugin::instance();
}
add_action( 'plugins_loaded', 'elemendas_addons' );

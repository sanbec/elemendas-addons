<?php
/**
 * This Elementor Addon plugin improves the search results archive page and adds awesome widgets.
 *
 * @package   Elemendas_Addons
 * @author    Santiago Becerra <santi@wpcombo.com>
 * @license   GPLv3 or later
 * @link      https://elemendas.com
 * @copyright 2022 Santiago Becerra
 *
 * @wordpress-plugin

 * Plugin Name:                Elemendas Addons
 * Plugin URI:                 https://elemendas.com/elemendas-addons/
 * Description:                Improving the search results archive page for Elementor.
 * Author:                     Elemendas, Santiago Becerra
 * Author URI:                 https://elemendas.com/
 * Text Domain:                elemendas-addons
 * License:                    GPLv3 or later
 * License URI:                https://www.gnu.org/licenses/gpl-3.0.txt
 * Elementor tested up to:     3.6.5
 * Elementor Pro tested up to: 3.7.1
 * Version:                    2.3.0
 * Requires at least and Requires PHP tags are declared at readme.txt and verified by a built-in function at /includes/plugin.php
 */
namespace Elemendas\Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define('ELEMENDAS_ADDONS_VERSION', '2.3.0' );
define('ELM_PLUGIN_URL', trailingslashit(plugins_url('/', __FILE__)));

function elemendas_addons() {
	// Load plugin file
	require_once( __DIR__ . '/includes/plugin.php' );

	// Run the plugin
	Plugin::instance();
}
add_action( 'plugins_loaded', 'Elemendas\Addons\elemendas_addons' );

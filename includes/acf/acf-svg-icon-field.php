<?php
/*
Module Name: SVG icon picker for Advanced Custom Fields
Based on: https://github.com/houke/acf-icon-picker
Description: Allows you to pick a SVG icon from the content of a predefined folder
*/

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('acf_svg_icon_field') ) :

class acf_svg_icon_field {

	function __construct() {

		$this->settings = array(
			'url'		=> plugin_dir_url( __FILE__ ),
			'path'		=> plugin_dir_path( __FILE__ )
		);

		add_action('acf/include_field_types', 	array($this, 'include_field_types'));

	}

	function include_field_types( $version = false ) {
		include_once('fields/acf-icon-picker.php');
	}

}

new acf_svg_icon_field();

endif;

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
			'icons_url' 	=> trailingslashit( wp_upload_dir()['baseurl'] ) ,
			'icons_path'	=> trailingslashit( wp_upload_dir()['basedir'] ),
			'plugin_url'	=> plugin_dir_url( __FILE__ )
//			'path'		=> plugin_dir_path( __FILE__ )
		);

		add_action('acf/include_field_types', 	array($this, 'include_field_types'));
		global $wp_filesystem;
		require_once ( ABSPATH . '/wp-admin/includes/file.php' );
		WP_Filesystem();
		$target = $this->settings['icons_path'] .'elemendas-svg-icons';
		// Create a folder to upload custom icons
		$uploads_dir = $target . '/Uploaded Icons';
		wp_mkdir_p( $uploads_dir );
		$zipfile = plugin_dir_path( __FILE__ ) . '/assets/img/elemendas-svg-icons.zip';
		if ($wp_filesystem->exists ($zipfile)) :
			$return = unzip_file( $zipfile, $target );
			if( is_wp_error( $return ) ) echo $return->get_error_message();
			wp_delete_file($zipfile);
		endif;

	}

	function include_field_types( $version = false ) {
		include_once('fields/acf-icon-picker.php');
	}
}

new acf_svg_icon_field();

endif;

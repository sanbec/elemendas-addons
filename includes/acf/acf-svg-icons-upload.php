<?php
/**
 * Menu Icons Upload module
 *
 * @package   MenuIconsUpload
 * @author    Santiago Becerra <santi@elemendas.com>
 * @license   GPL-3.0+
 * @link      https://elemendas.com
 * @copyright 2022 Santiago Becerra
 */
namespace Elemendas\Addons\MenuIcons;

final class Menu_Icons_Upload {

	const TRANSIENT_KEY = 'elmadd_icons_message';

	public static function init() {

		add_action( 'admin_menu', [ __CLASS__, 'add_admin_appearance_page' ] );
		add_action( 'admin_notices', [ __CLASS__, 'upload_icons_notice' ] );
		if ( ! empty( $_FILES['elm_icon_files']['name'][0] ) ) {
			check_admin_referer( 'elmadd_icons_upload', 'elmadd_icons_upload' );
			self::upload_icons();
			wp_redirect( wp_get_referer() );
		}
	}

	/*
	 * Add link to the elmadd-icons-upload at the appearance menu
	 */
	public static function add_admin_appearance_page() {

		function render_appearance_page() {
			?>

			<div class="wrap">
				<h2><?=__( 'Upload Menu Icons', 'elemendas-addons' )?></h2>
				<form method="POST" action="themes.php?page=elmadd-upload-custom-icons" enctype="multipart/form-data">
				<?php wp_nonce_field( 'elmadd_icons_upload', 'elmadd_icons_upload' ) ?>
					<div id="elm_icon_uploads">
						<label for="elm_icon_files"><?php echo esc_html__('Choose SVG to upload','elemendas-addons'); ?></label>
						<input type="file" id="elm_icon_files" name="elm_icon_files[]" accept=".svg" multiple>
						<div id="elm_icon_preview">
							<p><?php echo esc_html__('No files currently selected for upload','elemendas-addons'); ?></p>
						</div>
							<?php
							submit_button(
								__( 'Upload Icons', 'elemendas-addons' ),
								'secondary',
								'icons-upload',
								false
							);
							?>
					</div>
				</form>
			</div>
			<?php
		} // end appearance_page_html
		$upload_icons_hook = add_theme_page( // Adds a submenu page to the Appearance main menu.
			__( 'Upload Custom Menu Icons', 'elemendas-addons' ),
			__( 'Upload Menu Icons', 'elemendas-addons' ),
			'manage_options',
			'elmadd-upload-custom-icons',
			'Elemendas\Addons\MenuIcons\render_appearance_page'
		);
		add_action( 'admin_print_styles-' . $upload_icons_hook,  [ __CLASS__, 'enqueue_style' ]  );
		add_action( 'admin_print_footer_scripts-' . $upload_icons_hook,  [ __CLASS__, 'enqueue_script' ]  );

	} // end add_admin_link

	/**
	 * Upload icons
	 *
	 * @access protected
	 *
	 * @param  array $values Settings values.
	 *
	 */
	protected static function upload_icons( ) {
		$files = $_FILES['elm_icon_files'];
		add_filter( 'upload_dir',  [__CLASS__, 'menu_icons_upload_dir'] );
		add_filter( 'upload_mimes',  [__CLASS__, 'svg_mime_types'] );

		$iconsUploaded = 0;
		foreach ($files['name'] as $key => $value) {
			if ($files['name'][$key]) {
				$file = array(
				'name' => $files['name'][$key],
				'type' => $files['type'][$key],
				'tmp_name' => $files['tmp_name'][$key],
				'error' => $files['error'][$key],
				'size' => $files['size'][$key]
				);
				$_FILES = array ('elm_icon_files' => $file);
				foreach ($_FILES as $file => $array) {
					if ( self::icon_media_handle_upload($file) ) {
						$iconsUploaded++;
					}
				}
			}
		}

		remove_filter( 'upload_mimes', [__CLASS__, 'svg_mime_types'] );
		remove_filter( 'upload_dir',  [__CLASS__, 'menu_icons_upload_dir'] );

		set_transient( self::TRANSIENT_KEY, $iconsUploaded, 30 );
	}

	public static function svg_mime_types( $mimes ) {
		// SVG allowed mime types.
		$mimes['svg']  = 'image/svg+xml';
		return $mimes;
	}

	public static function menu_icons_upload_dir( $dir ) {
		return array(
			'path'   => $dir['basedir'] . '/elemendas-svg-icons/Uploaded Icons',
			'url'    => $dir['baseurl'] . '/elemendas-svg-icons/Uploaded Icons',
			'subdir' => '',
		) + $dir;

	}
	private static function icon_media_handle_upload($file_handler) {
		// check to make sure its a successful upload


		if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');

		$attach_id = media_handle_upload( $file_handler, 0);
		if ( is_wp_error( $attach_id ) ) {
			// There was an error uploading the image.
			$error_string = $attach_id->get_error_message();
			echo '<div id="message" class="error"><p>' .$_FILES[$file_handler]['name']. ': ' . $error_string . '</p></div>';
			return false;
		} else {
			// The image was uploaded successfully!
		}
		return true;

	}

	/**
	 * Upload icons notice
	 *
	 * @wp_hook action admin_notices
	 */
	public static function upload_icons_notice() {

		$iconsUploaded = get_transient( self::TRANSIENT_KEY );

		if ( $iconsUploaded > 0 ) {
			$message = sprintf ( _n( 'Icon successfully uploaded.', '%s icons have been successfully uploaded.', $iconsUploaded , 'elemendas-addons' ), number_format_i18n( $iconsUploaded ));
			printf(
				'<div class="updated notice is-dismissible"><p>%s</p></div>',  wp_kses( $message , array( 'strong' => true ) )
			);
		}

		delete_transient( self::TRANSIENT_KEY );
	}

	/**
	 * Enqueue scripts & styles for Appearance > Menus page
	 *
	 * @since   0.3.0
	 * @wp_hook action admin_enqueue_scripts
	 */
	public static function enqueue_style() {
		$url = plugin_dir_url( __FILE__ );

		wp_enqueue_style(
			'acf-svg-icon-settings',
			"{$url}assets/css/uploadSVG.css",
			false,
			ELEMENDAS_ADDONS_VERSION
		);
	}
	/**
	 * Enqueue scripts & styles for Appearance > Menus page
	 *
	 * @since   0.3.0
	 * @wp_hook action admin_enqueue_scripts
	 */
	public static function enqueue_script() {
		$url = plugin_dir_url( __FILE__ );

		wp_enqueue_script(
			'acf-svg-icon-settings',
			"{$url}assets/js/uploadSVG.js",
			['jquery'],
			ELEMENDAS_ADDONS_VERSION,
			true
		);
	}
}

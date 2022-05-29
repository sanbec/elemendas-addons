<?php
namespace Elemendas_Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Plugin class.
 *
 * The main class that initiates and runs the addon.
 *
 * @since 1.0.0
 */
final class Plugin {

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the addon.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.5.0';

	/**
	 * Minimum Elementor Pro Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor Pro version required to run the addon.
	 */
	const MINIMUM_ELEMENTOR_PRO_VERSION = '3.6.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 * @var \Elemendas_Addons\Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @return \Elemendas_Addon\Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * Perform some compatibility checks to make sure basic requirements are meet.
	 * If all compatibility checks pass, initialize the functionality.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		if ( $this->is_compatible() ) {
			add_action( 'elementor/init', [ $this, 'init' ] );
		}
	}

	/**
     * Check if a plugin is installed
     *
     * @since 1.0.0
     */
    public function is_plugin_installed($basename) {
        if (!function_exists('get_plugins')) {
            include_once ABSPATH . '/wp-admin/includes/plugin.php';
        }
        $installed_plugins = get_plugins();
        return isset($installed_plugins[$basename]);
    }

   /**
     * Check if a plugin is active
     *
     * @since 1.0.0
     */
    public function is_plugin_active($basename) {
        if (!function_exists('is_plugin_active')) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		return is_plugin_active($basename);
	}

	public function elemendas_admin_styles() {
		wp_enqueue_style( 'elemendas-admin', ELM_PLUGIN_URL . 'assets/css/admin.css', false, ELEMENDAS_ADDONS_VERSION );
	}

	public function elemendas_editor_styles() {
		wp_enqueue_style( 'elemendas-editor-fa', '/wp-content/plugins/elementor/assets/lib/font-awesome/css/all.min.css');
		wp_enqueue_style( 'elemendas-editor', ELM_PLUGIN_URL . 'assets/css/editor.css', false, ELEMENDAS_ADDONS_VERSION );
	}

	public function elemendas_preview_styles() {
		wp_enqueue_style( 'elemendas-preview', ELM_PLUGIN_URL . 'assets/css/preview.css', false, ELEMENDAS_ADDONS_VERSION );
	}

	/**
	 * Compatibility Checks
	 *
	 * Checks whether the site meets the addon requirement.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function is_compatible() {
        add_action( 'admin_enqueue_scripts', [ $this, 'elemendas_admin_styles' ] );

		// Check if Elementor is installed
		if ( !$this->is_plugin_installed ( 'elementor/elementor.php' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return false;
		}
		// Check if Elementor is activated
		if ( !$this->is_plugin_active ( 'elementor/elementor.php' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_disabled_main_plugin' ] );
			return false;
		}
		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return false;
		}
		// Check if Elementor Pro is installed
		if ( !$this->is_plugin_installed ( 'elementor-pro/elementor-pro.php' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_pro_plugin' ] );
			return true;
		}
		// Check if Elementor Pro is activated
		if ( !$this->is_plugin_active ( 'elementor-pro/elementor-pro.php' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_disabled_pro_plugin' ] );
			return true;
		}
		// Check for required Elementor Pro version
		if ( !version_compare( ELEMENTOR_PRO_VERSION, self::MINIMUM_ELEMENTOR_PRO_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_pro_version' ] );
			return true;
		}
		return true;
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function compatibility_admin_notice( $item, $action, $requirement, $action_url = "", $min_version = "" ) {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
		$itemName = ucwords(str_replace("-"," ",$item));

		switch ($action) {
			case "install":
				if ("" === $action_url) $action_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin='.$item), 'install-plugin_'.$item);
				if ("requires" === $requirement) {
					/* translators: 1: Plugin name 2: Elementor/Elementor Pro */
					$message_text = esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elemendas-addons' );
				} else {
					/* translators: 1: Plugin name 2: Elementor/Elementor Pro */
					$message_text = esc_html__( '"%1$s" recommends "%2$s" to be installed and activated.', 'elemendas-addons' );
				}
				/* translators: %s: Elementor/Elementor Pro */
				$button_text = sprintf(__('Install %s', 'elemendas-addons'), $itemName );
				break;
			case "activate":
				$plugin = $item.'/'.$item.'.php';
				if ("" === $action_url) $action_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);
				if ("requires" === $requirement) {
					/* translators: 1: Plugin name 2: Elementor/Elementor Pro */
					$message_text = esc_html__( '"%1$s" requires "%2$s" to be activated.', 'elemendas-addons' );
				} else {
					/* translators: 1: Plugin name 2: Elementor/Elementor Pro */
					$message_text = esc_html__( '"%1$s" recommends "%2$s" to be activated.', 'elemendas-addons' );
				}
				/* translators: %s: Elementor/Elementor Pro */
				$button_text = sprintf(__('Activate %s', 'elemendas-addons'), $itemName );
				break;
			case "update":
				$plugin = $item.'/'.$item.'.php';
				if ("" === $action_url) $action_url = wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin='.$plugin), 'upgrade-plugin_'.$plugin);
				/* translators: 1: Plugin name 2: Elementor/Elementor Pro 3: Required Elementor version */
				$message_text = esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elemendas-addons' );
				/* translators: %s: Elementor/Elementor Pro */
				$button_text = sprintf(__('Update %s', 'elemendas-addons'), $itemName );
				break;
		}

		switch ($action) {
			case "install":
			case "activate":
				$message = sprintf(
					$message_text,
					'<strong>' . esc_html__( 'Elemendas Addons', 'elemendas-addons' ) . '</strong>',
					'<strong>' . $itemName . '</strong>'
				);
				break;
			case "update":
				$message = sprintf(
					$message_text,
						'<strong>' . esc_html__( 'Elemendas Addons', 'elemendas-addons' ) . '</strong>',
					'<strong>' . 'Elementor' . '</strong>',
					$min_version
				);
		}


        $button = '<a href="' . esc_url( $action_url ) . '" class="button-primary">' . esc_html( $button_text ) . '</a>';
		if ("requires" === $requirement) {
			printf('<div class="error elemendas-error">%1$s%2$s</div>', __($message), $button);
		} else {
			printf('<div class="notice notice-warning is-dismissible elemendas-error">%1$s%2$s</div>', __($message), $button);
		}

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		$this->compatibility_admin_notice( 'elementor','install','requires');
	}

	/**
	 * Admin notice warning when the site doesn't have Elementor activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_disabled_main_plugin() {
		$this->compatibility_admin_notice( 'elementor','activate','requires');
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		$this->compatibility_admin_notice( 'elementor','update','requires', '' , self::MINIMUM_ELEMENTOR_VERSION);
	}

	/**
	 * Admin notice warning when the site doesn't have Elementor Pro installed.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_pro_plugin() {
		$this->compatibility_admin_notice( 'elementor-pro','install','recommends', 'https://trk.elementor.com/24242' );
	}

	/**
	 * Admin notice warning when the site doesn't have Elementor Pro activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_disabled_pro_plugin() {
		$this->compatibility_admin_notice( 'elementor-pro','activate','recommends');
	}

	/**
	 * Admin notice warning when the site doesn't have a minimum required Elementor Pro version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_pro_version() {
		$this->compatibility_admin_notice( 'elementor-pro','update','requires', '' , self::MINIMUM_ELEMENTOR_PRO_VERSION);
	}

	/**
	 * Initialize
	 *
	 * Load the addons functionality only after Elementor is initialized.
	 *
	 * Fired by `elementor/init` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {
		// Add widgets
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		// Add controls
		add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );
		// Enqueue styles for he Elementor editor bar
        add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elemendas_editor_styles' ] );
		// Enqueue styles for he Elementor editor preview
        add_action( 'elementor/preview/enqueue_styles', [ $this, 'elemendas_preview_styles' ] );
		// Initiate extensions to existing elements
		self::elemendas_init_extensions();
		require_once( __DIR__ . '/acf/menu-fields.php' );
		require_once( __DIR__ . '/acf/acf-svg-icon-field.php' );
		// Load settings.
		require_once( __DIR__ . '/acf/acf-svg-icon-settings.php' );
		Menu_Icons_Settings::init();
	}

	/**
	 * Register Widgets
	 *
	 * Load widgets files and register new Elementor widgets.
	 *
	 * Fired by `elementor/widgets/register` action hook.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {
		require_once( __DIR__ . '/widgets/added/search-results-highlighted.php' );
		$widgets_manager->register( new Search_Results_Highlighted() );

		require_once( __DIR__ . '/widgets/added/leaveslist.php' );
		$widgets_manager->register( new Leaves_List() );

		require_once( __DIR__ . '/widgets/added/carousel3D.php' );
		$widgets_manager->register( new Carousel_3D() );
	}

	/**
	 * Register Controls
	 *
	 * Load controls files and register new Elementor controls.
	 *
	 * Fired by `elementor/controls/register` action hook.
	 *
	 * @param \Elementor\Controls_Manager $controls_manager Elementor controls manager.
	 */
	public function register_controls( $controls_manager ) {
		require_once( __DIR__ . '/controls/quotation-marks.php' );
		$controls_manager->register( new Quotation_Control() );

		require_once( __DIR__ . '/controls/highlighter.php' );
		$controls_manager->register( new Highlighter_Control() );

		require_once( __DIR__ . '/controls/border-style.php' );
		$controls_manager->register( new Border_Style_Control() );
	}
	
	public function elemendas_init_extensions(  ) {
		// Include extension classes
		require_once( __DIR__ . '/widgets/extended/search-results-archive-title.php' );
		Search_Results_Archive_Title::init();
		require_once( __DIR__ . '/widgets/extended/fancy-nav-menu.php' );
		Fancy_Nav_Menu::init();


		$extensions_array = [ 'Search_Results_Archive_Title','Fancy_Nav_Menu'];

	}
}

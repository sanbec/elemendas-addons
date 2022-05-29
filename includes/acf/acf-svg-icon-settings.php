<?php
namespace Elemendas_Addons;

/**
 * Settings
 *
 * @package Menu_Icons
 * @author  Dzikri Aziz <kvcrvt@gmail.com>
 */

/**
 * Menu Icons Settings module
 */
final class Menu_Icons_Settings {

	const UPDATE_KEY = 'menu-icons-settings-update';

	const RESET_KEY = 'menu-icons-settings-reset';

	const TRANSIENT_KEY = 'menu_icons_message';

	/**
	 * Default setting values
	 *
	 * @since  0.3.0
	 * @var   array
	 * @access protected
	 */
	protected static $defaults = array(
		'global' => array(
			'icon_types' => array( 'dashicons' ),
		),
	);

	/**
	 * Setting values
	 *
	 * @since  0.3.0
	 * @var   array
	 * @access protected
	 */
	protected static $settings = array();

	/**
	 * Script dependencies
	 *
	 * @since  0.9.0
	 * @access protected
	 * @var    array
	 */
	protected static $script_deps = array( 'jquery' );

	/**
	 * Settings init
	 *
	 * @since 0.3.0
	 */
	public static function init() {

		/**
		 * Allow themes/plugins to override the default settings
		 *
		 * @since 0.9.0
		 *
		 * @param array $default_settings Default settings.
		 */

		unset( $value );

		/**
		 * Allow themes/plugins to override the settings
		 *
		 * @since 0.9.0
		 *
		 * @param array $settings Menu Icons settings.
		 */
		self::$settings = apply_filters( 'menu_icons_settings', self::$settings );

		add_action( 'load-nav-menus.php', array( __CLASS__, '_load_nav_menus' ), 1 );
		add_action( 'wp_ajax_menu_icons_update_settings', array( __CLASS__, '_ajax_menu_icons_update_settings' ) );
	}


	/**
	 * Get ID of menu being edited
	 *
	 * @since  0.7.0
	 * @since  0.8.0 Get the recently edited menu from user option.
	 *
	 * @return int
	 */
	public static function get_current_menu_id() {
		global $nav_menu_selected_id;

		if ( ! empty( $nav_menu_selected_id ) ) {
			return $nav_menu_selected_id;
		}

		if ( is_admin() && isset( $_REQUEST['menu'] ) ) {
			$menu_id = absint( $_REQUEST['menu'] );
		} else {
			$menu_id = absint( get_user_option( 'nav_menu_recently_edited' ) );
		}

		return $menu_id;
	}

	/**
	 * Get menu settings
	 *
	 * @since  0.3.0
	 *
	 * @param  int $menu_id
	 *
	 * @return array
	 */
	public static function get_menu_settings( $menu_id ) {
		$menu_settings = self::get( sprintf( 'menu_%d', $menu_id ) );
		$menu_settings = apply_filters( 'menu_icons_menu_settings', $menu_settings, $menu_id );

		if ( ! is_array( $menu_settings ) ) {
			$menu_settings = array();
		}

		return $menu_settings;
	}
	/**
	 * Get value of a multidimensional array
	 *
	 * @since  0.1.0
	 * @param  array $array Haystack
	 * @param  array $keys  Needles
	 * @return mixed
	 */
	static function get_array_value_deep( array $array, array $keys ) {
		if ( empty( $array ) || empty( $keys ) ) {
			return $array;
		}

		foreach ( $keys as $idx => $key ) {
			unset( $keys[ $idx ] );

			if ( ! isset( $array[ $key ] ) ) {
				return null;
			}

			if ( ! empty( $keys ) ) {
				$array = $array[ $key ];
			}
		}

		if ( ! isset( $array[ $key ] ) ) {
			return null;
		}

		return $array[ $key ];
	}



	/**
	 * Get setting value
	 *
	 * @since  0.3.0
	 * @return mixed
	 */
	public static function get() {
		$args = func_get_args();

		return self::get_array_value_deep( self::$settings, $args );
	}

	/**
	 * Prepare wp-admin/nav-menus.php page
	 *
	 * @since   0.3.0
	 * @wp_hook action load-nav-menus.php
	 */
	public static function _load_nav_menus() {


		add_action( 'admin_enqueue_scripts', array( __CLASS__, '_enqueue_assets' ), 99 );
		/**
		 * Allow settings meta box to be disabled.
		 *
		 * @since 0.4.0
		 *
		 * @param bool $disabled Defaults to FALSE.
		 */
		$settings_disabled = apply_filters( 'menu_icons_disable_settings', false );
		if ( true === $settings_disabled ) {
			return;
		}
//*/

		self::_maybe_update_settings();
		self::_add_settings_meta_box();
//*/
	}

	/**
	 * Update settings
	 *
	 * @since 0.3.0
	 */
	public static function _maybe_update_settings() {
		if ( ! empty( $_POST['menu-icons']['settings'] ) ) {
			check_admin_referer( self::UPDATE_KEY, self::UPDATE_KEY );

			$redirect_url = self::_update_settings( $_POST['menu-icons']['settings'] ); // Input var okay.
			wp_redirect( $redirect_url );
		} elseif ( ! empty( $_REQUEST[ self::RESET_KEY ] ) ) {
			check_admin_referer( self::RESET_KEY, self::RESET_KEY );
			wp_redirect( self::_reset_settings() );
		}
	}

	/**
	 * Update settings
	 *
	 * @since  0.7.0
	 * @access protected
	 *
	 * @param  array $values Settings values.
	 *
	 * @return string    Redirect URL.
	 */
	protected static function _update_settings( $values ) {
		update_option(
			'menu-icons',
			wp_parse_args(
				kucrut_validate( $values ),
				self::$settings
			)
		);
		set_transient( self::TRANSIENT_KEY, 'updated', 30 );

		$redirect_url = remove_query_arg(
			array( 'menu-icons-reset' ),
			wp_get_referer()
		);

		return $redirect_url;
	}

	/**
	 * Reset settings
	 *
	 * @since  0.7.0
	 * @access protected
	 * @return string    Redirect URL.
	 */
	protected static function _reset_settings() {
		delete_option( 'menu-icons' );
		set_transient( self::TRANSIENT_KEY, 'reset', 30 );

		$redirect_url = remove_query_arg(
			array( self::RESET_KEY, 'menu-icons-updated' ),
			wp_get_referer()
		);

		return $redirect_url;
	}

	/**
	 * Settings meta box
	 *
	 * @since  0.3.0
	 * @access private
	 */
	private static function _add_settings_meta_box() {
		add_meta_box(
			'menu-icons-settings',
			__( 'Upload Custom Icons', 'elemendas-addons' ),
			array( __CLASS__, '_meta_box' ),
			'nav-menus',
			'side',
			'low',
			array()
		);
	}

	/**
	 * Update settings via ajax
	 *
	 * @since   0.7.0
	 * @wp_hook action wp_ajax_menu_icons_update_settings
	 */
	public static function _ajax_menu_icons_update_settings() {
		check_ajax_referer( self::UPDATE_KEY, self::UPDATE_KEY );

		if ( empty( $_POST['menu-icons']['settings'] ) ) {
			wp_send_json_error();
		}

		$redirect_url = self::_update_settings( $_POST['menu-icons']['settings'] ); // Input var okay.
		wp_send_json_success( array( 'redirectUrl' => $redirect_url ) );
	}

	/**
	 * Settings meta box
	 *
	 * @since 0.3.0
	 */
	public static function _meta_box() {
		?>
			<div id="elm_icon_uploads">
				<label for="elm_icon_files">Choose SVG to upload</label>
				<input type="file" id="elm_icon_files" name="elm_icon_files" accept=".svg" multiple>
				<div id="elm_icon_preview">
					<p>No files currently selected for upload</p>
				</div>
			</div>
		<?php
	}


	/**
	 * Get settings sections
	 *
	 * @since  0.3.0
	 * @uses   apply_filters() Calls 'menu_icons_settings_sections'.
	 * @return array
	 */
	public static function get_fields() {
		$menu_id    = self::get_current_menu_id();
//		$icon_types = wp_list_pluck( Menu_Icons::get( 'types' ), 'name' );

//		asort( $icon_types );

		$sections = array(
			'global' => array(
				'id'          => 'global',
				'title'       => __( 'Global', 'elemendas-addons' ),
				'description' => __( 'Global settings', 'elemendas-addons' ),
				'fields'      => array(
					array(
						'id'      => 'icon_types',
						'type'    => 'checkbox',
						'label'   => __( 'Icon Types', 'elemendas-addons' ),
//						'choices' => $icon_types,
						'value'   => self::get( 'global', 'icon_types' ),
					),
					array(
						'id'        => 'fa5_extra_icons',
						'type'      => 'textarea',
						'label'     => __( 'FA5 Custom Icon Classes', 'elemendas-addons' ),
						'value'     => self::get( 'global', 'fa5_extra_icons' ),
						'help_text' => '( comma separated icons )',
					),
				),
				'args'        => array(),
			),
		);

		if ( ! empty( $menu_id ) ) {
			$menu_term     = get_term( $menu_id, 'nav_menu' );
			$menu_key      = sprintf( 'menu_%d', $menu_id );
			$menu_settings = self::get_menu_settings( $menu_id );

			$sections['menu'] = array(
				'id'          => $menu_key,
				'title'       => __( 'Current Menu', 'elemendas-addons' ),
				'description' => sprintf(
					__( '"%s" menu settings', 'elemendas-addons' ),
					apply_filters( 'single_term_title', $menu_term->name )
				),
				'fields'      => self::get_settings_fields( $menu_settings ),
				'args'        => array( 'inline_description' => true ),
			);
		}

		return apply_filters( 'menu_icons_settings_sections', $sections, $menu_id );
	}

	/**
	 * Get settings fields
	 *
	 * @since  0.4.0
	 *
	 * @param  array $values Values to be applied to each field.
	 *
	 * @uses   apply_filters()          Calls 'menu_icons_settings_fields'.
	 * @return array
	 */
	public static function get_settings_fields( array $values = array() ) {
		$fields = array(
			'hide_label'     => array(
				'id'      => 'hide_label',
				'type'    => 'select',
				'label'   => __( 'Hide Label', 'elemendas-addons' ),
				'default' => '',
				'choices' => array(
					array(
						'value' => '',
						'label' => __( 'No', 'elemendas-addons' ),
					),
					array(
						'value' => '1',
						'label' => __( 'Yes', 'elemendas-addons' ),
					),
				),
			),
			'position'       => array(
				'id'      => 'position',
				'type'    => 'select',
				'label'   => __( 'Position', 'elemendas-addons' ),
				'default' => 'before',
				'choices' => array(
					array(
						'value' => 'before',
						'label' => __( 'Before', 'elemendas-addons' ),
					),
					array(
						'value' => 'after',
						'label' => __( 'After', 'elemendas-addons' ),
					),
				),
			),
			'vertical_align' => array(
				'id'      => 'vertical_align',
				'type'    => 'select',
				'label'   => __( 'Vertical Align', 'elemendas-addons' ),
				'default' => 'middle',
				'choices' => array(
					array(
						'value' => 'super',
						'label' => __( 'Super', 'elemendas-addons' ),
					),
					array(
						'value' => 'top',
						'label' => __( 'Top', 'elemendas-addons' ),
					),
					array(
						'value' => 'text-top',
						'label' => __( 'Text Top', 'elemendas-addons' ),
					),
					array(
						'value' => 'middle',
						'label' => __( 'Middle', 'elemendas-addons' ),
					),
					array(
						'value' => 'baseline',
						'label' => __( 'Baseline', 'elemendas-addons' ),
					),
					array(
						'value' => 'text-bottom',
						'label' => __( 'Text Bottom', 'elemendas-addons' ),
					),
					array(
						'value' => 'bottom',
						'label' => __( 'Bottom', 'elemendas-addons' ),
					),
					array(
						'value' => 'sub',
						'label' => __( 'Sub', 'elemendas-addons' ),
					),
				),
			),
			'font_size'      => array(
				'id'          => 'font_size',
				'type'        => 'number',
				'label'       => __( 'Font Size', 'elemendas-addons' ),
				'default'     => '1.2',
				'description' => 'em',
				'attributes'  => array(
					'min'  => '0.1',
					'step' => '0.1',
				),
			),
			'svg_width'      => array(
				'id'          => 'svg_width',
				'type'        => 'number',
				'label'       => __( 'SVG Width', 'elemendas-addons' ),
				'default'     => '1',
				'description' => 'em',
				'attributes'  => array(
					'min'  => '.5',
					'step' => '.1',
				),
			),
			'image_size'     => array(
				'id'      => 'image_size',
				'type'    => 'select',
				'label'   => __( 'Image Size', 'elemendas-addons' ),
				'default' => 'thumbnail',
				'choices' => ['value' => 'value','label' => 'label'],
			),
		);

		$fields = apply_filters( 'menu_icons_settings_fields', $fields );

		foreach ( $fields as &$field ) {
			if ( isset( $values[ $field['id'] ] ) ) {
				$field['value'] = $values[ $field['id'] ];
			}

			if ( ! isset( $field['value'] ) && isset( $field['default'] ) ) {
				$field['value'] = $field['default'];
			}
		}

		unset( $field );

		return $fields;
	}

	/**
	 * Get processed settings fields
	 *
	 * @since  0.3.0
	 * @access private
	 * @return array
	 */
	private static function _get_fields() {

		$keys     = array( 'menu-icons', 'settings' );
		$sections = self::get_fields();

		foreach ( $sections as &$section ) {
			$_keys = array_merge( $keys, array( $section['id'] ) );
			$_args = array_merge( array( 'keys' => $_keys ), $section['args'] );


			unset( $field );
		}

		unset( $section );

		return $sections;
	}

	/**
	 * Enqueue scripts & styles for Block Icons
	 *
	 * @since   0.3.0
	 * @wp_hook action enqueue_block_assets
	 */
	public static function _enqueue_font_awesome() {
		$url = Menu_Icons::get( 'url' );

		wp_register_style(
			'font-awesome-5',
			"{$url}css/fontawesome/css/all.min.css"
		);
	}

	/**
	 * Enqueue scripts & styles for Appearance > Menus page
	 *
	 * @since   0.3.0
	 * @wp_hook action admin_enqueue_scripts
	 */
	public static function _enqueue_assets() {
		$url = plugin_dir_url( __FILE__ );

		wp_enqueue_style(
			'acf-svg-icon-settings',
			"{$url}assets/css/uploadSVG.css",
			false,
			ELEMENDAS_ADDONS_VERSION
		);

		wp_enqueue_script(
			'acf-svg-icon-settings',
			"{$url}assets/js/uploadSVG.js",
			self::$script_deps,
			ELEMENDAS_ADDONS_VERSION,
			true
		);

		$customizer_url = add_query_arg(
			array(
				'autofocus[section]' => 'custom_css',
				'return'             => admin_url( 'nav-menus.php' ),
			),
			admin_url( 'customize.php' )
		);

		/**
		 * Allow plugins/themes to filter the settings' JS data
		 *
		 * @since 0.9.0
		 *
		 * @param array $js_data JS Data.
		 */
		$menu_current_theme = '';
		$theme              = wp_get_theme();
		if ( ! empty( $theme ) ) {
			if ( is_child_theme() && $theme->parent() ) {
				$menu_current_theme = $theme->parent()->get( 'Name' );
			} else {
				$menu_current_theme = $theme->get( 'Name' );
			}
		}
		$upsell_notices = array();
		$box_data = '<div id="menu-icons-sidebar">';

		if ( ( $menu_current_theme != 'Neve' ) ) {
			$upsell_notices['neve'] = array(
				'content' => wp_sprintf( '<div class="menu-icon-notice-popup-img"><img src="%s"/></div><div class="menu-icon-notice-popup"><h4>%s</h4>%s', plugin_dir_url( __FILE__ ) . '../images/neve-theme.jpg', __( 'Check-out our latest lightweight FREE theme - Neve', 'elemendas-addons' ), __( 'Neve’s mobile-first approach, compatibility with AMP and popular page-builders makes website building accessible for everyone.', 'elemendas-addons' ) ),
				'url' => add_query_arg(
					array(
						'theme' => 'neve',
					),
					admin_url( 'theme-install.php' )
				),
				'btn_text' => __( 'Preview Neve', 'elemendas-addons' ),
			);
		}

		if ( ! in_array( 'otter-blocks/otter-blocks.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			$upsell_notices['otter-blocks'] = array(
				'content' => wp_sprintf( '<div class="menu-icon-notice-popup-img"><img src="%s"/></div><div class="menu-icon-notice-popup"><h4>%s</h4>%s', plugin_dir_url( __FILE__ ) . '../images/otter-block.png', __( 'Build professional pages with Otter Blocks', 'elemendas-addons' ), __( 'Otter is a dynamic collection of page building blocks and templates,  covering all the elements you need to build your WordPress site.', 'elemendas-addons' ) ),
				'url' => add_query_arg(
					array(
						'tab'       => 'plugin-information',
						'plugin'    => 'otter-blocks',
						'TB_iframe' => true,
						'width'     => 772,
						'height'    => 551,
					),
					admin_url( 'plugin-install.php' )
				),
				'btn_text' => __( 'Preview Otter Blocks', 'elemendas-addons' ),
			);
		}

		if ( ! empty( $upsell_notices ) ) {
			$rand_key                     = array_rand( $upsell_notices );
			$menu_upgrade_hestia_box_text = $upsell_notices[ $rand_key ]['content'];

			$box_data               .= '<div class="nv-upgrade-notice postbox new-card">';
			$box_data               .= wp_kses_post( wpautop( $menu_upgrade_hestia_box_text ) );
			$box_data               .= '<a class="button" href="' . $upsell_notices[ $rand_key ]['url'] . '" target="_blank">' . $upsell_notices[ $rand_key ]['btn_text'] . '</a>';
			$box_data               .= '</div></div>';
		}
		$js_data = apply_filters(
			'menu_icons_settings_js_data',
			array(
				'text'           => array(
					'title'        => __( 'Select Icon', 'elemendas-addons' ),
					'select'       => __( 'Select', 'elemendas-addons' ),
					'remove'       => __( 'Remove', 'elemendas-addons' ),
					'change'       => __( 'Change', 'elemendas-addons' ),
					'all'          => __( 'All', 'elemendas-addons' ),
					'preview'      => __( 'Preview', 'elemendas-addons' ),
					'settingsInfo' => sprintf(
						'<div> %1$s <p>' . esc_html__( 'Please note that the actual look of the icons on the front-end will also be affected by the style of your active theme. You can add your own CSS using %2$s or a plugin such as %3$s if you need to override it.', 'elemendas-addons' ) . '</p></div>',
						$box_data,
						sprintf(
							'<a href="%s">%s</a>',
							esc_url( $customizer_url ),
							esc_html__( 'the customizer', 'elemendas-addons' )
						),
						'<a target="_blank" href="https://wordpress.org/plugins/advanced-css-editor/">Advanced CSS Editor</a>'
					),
				),
				'settingsFields' => self::get_settings_fields(),
				'activeTypes'    => self::get( 'global', 'icon_types' ),
				'ajaxUrls'       => array(
					'update' => add_query_arg( 'action', 'menu_icons_update_settings', admin_url( '/admin-ajax.php' ) ),
				),
				'menuSettings'   => self::get_menu_settings( self::get_current_menu_id() ),
			)
		);

		wp_localize_script( 'menu-icons', 'menuIcons', $js_data );
	}
}

<?php
namespace Elemendas_Addons;
use ElementorPro\Plugin;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

class Fancy_Nav_Menu {
	protected static $nav_menu_index = 1;
	private static $activate_root_menu = false;

	public static function init() {
		// add control to activate root menu (The first level item appears active if one of its descendants is active.)
		add_action( 'elementor/element/nav-menu/section_layout/before_section_end',  [ __CLASS__, 'add_root_menu_item_control' ] );

		// updating widget color controls in the style section
		add_action( 'elementor/element/nav-menu/section_style_dropdown/before_section_end',  [ __CLASS__, 'update_style_section_color_controls' ] );


		// updating render funtions
		add_filter( 'elementor/widget/render_content',  [ __CLASS__, 'render_fancy_nav_menu'], 10, 2 );

		// add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ __CLASS__, 'fancy_nav_menu_style' ] );

    }

    public static function fancy_nav_menu_style() {
		wp_enqueue_style( 'fancy-nav-menu', plugins_url( 'assets/css/fancy-nav-menu.css', __FILE__ ), false, ELEMENDAS_ADDONS_VERSION );
	}


	public static function add_root_menu_item_control($element) {

		$element->add_control(
			'activate_root_menu',
			[
				'label' => esc_html__( 'Activate root menu', 'elemendas-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'The first level item appears active if one of its descendants is active', 'elemendas-addons' ),
				'return_value' => 'true',
				'separator' => 'before',
			]
		);
	} // public static function add_root_menu_item_control


	public static function update_style_section_color_controls ($element) {

	/*****************************
	* BEGIN Main menu style Tab *
	*****************************/
		//Update color controls

		// BEGIN menu--main (horizontal or vertical)

		// normal text color
		$element->update_control(
			'color_menu_item',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-nav-menu--main .elementor-item' => 'color:  var( --elm-normal-color, {{VALUE}} ); fill: {{VALUE}};',
				],
			]
		);

		// hover text color
		$element->update_control(
			'color_menu_item_hover',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-nav-menu--main .elementor-item:hover,
					 {{WRAPPER}} .elementor-nav-menu--main .elementor-item.highlighted,
					 {{WRAPPER}} .elementor-nav-menu--main .elementor-item:focus' => 'color: var( --elm-hover-color, {{VALUE}} ); fill: {{VALUE}};',
					'{{WRAPPER}} .elementor-nav-menu--main .elementor-item.elementor-item-active' => 'color: var( --elm-active-color, var( --elm-hover-color, {{VALUE}} )); fill: {{VALUE}};',
				],

			]
		);
		// hover pointer color
		$element->update_control(
			'pointer_color_menu_item_hover',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-nav-menu--main:not(.e--pointer-framed) .elementor-item:before,
					 {{WRAPPER}} .elementor-nav-menu--main:not(.e--pointer-framed) .elementor-item:after' => 'background-color: var( --elm-hover-color, {{VALUE}} )',
					'{{WRAPPER}} .e--pointer-framed .elementor-item:before,
					 {{WRAPPER}} .e--pointer-framed .elementor-item:after' => 'border-color: var( --elm-hover-color, {{VALUE}} )',
				],
			]
		);
		// active text color
		$element->update_control(
			'color_menu_item_active',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-nav-menu--main:not(.e--pointer-background) .elementor-item.elementor-item-active' => 'color: var( --elm-active-color, {{VALUE}} )',
					'{{WRAPPER}} .elementor-nav-menu--main.e--pointer-background .elementor-item.elementor-item-active' => 'color: {{VALUE}}',
				],
			]
		);
		// active pointer color
		$element->update_control(
			'pointer_color_menu_item_active',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-nav-menu--main:not(.e--pointer-framed) .elementor-item.elementor-item-active:before,
					{{WRAPPER}} .elementor-nav-menu--main:not(.e--pointer-framed) .elementor-item.elementor-item-active:after' => 'background-color: var( --elm-active-color, {{VALUE}} )',
					'{{WRAPPER}} .e--pointer-framed .elementor-item.elementor-item-active:before,
					{{WRAPPER}} .e--pointer-framed .elementor-item.elementor-item-active:after' => 'border-color: var( --elm-active-color, {{VALUE}} )',
				],
			]
		);

		// section_style_dropdown menu--dropdown (dropdown or menu--main submenu)
		// normal text color
		$element->update_control(
			'color_dropdown_item',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-nav-menu--dropdown a, {{WRAPPER}} .elementor-menu-toggle' => 'color: var( --elm-normal-color, {{VALUE}} )',
				],
			]
		);
		// hover background color
		$element->update_control(
			'background_color_dropdown_item_hover',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-nav-menu--dropdown a:hover,
					 {{WRAPPER}} .elementor-nav-menu--dropdown a.highlighted' => 'background-color: var( --elm-hover-color, {{VALUE}} )',
				],
			]
		);

		// hover background color
		$element->update_control(
			'background_color_dropdown_item_active',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-nav-menu--dropdown a.elementor-item-active' => 'background-color: var( --elm-active-color, {{VALUE}} )',
				],
			]
		);



	// END Main menu style Tab. Note that this section will be followed by the style title section inherited from the heading widget.


	} // public static function update_style_section_color_controls

	public static function update_size_control ($element) {

		$element->update_control(
			'size',
			[
				'description' =>  __('This setting will have no effect if the font size is set in the typography control', 'elemendas-addons'),
			]
		);
	// END Style section
	} // END protected function register_controls

	private static function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}
	private static function get_nav_menu_index() {
		return self::$nav_menu_index++;
	}

	// BEGIN render_fancy_nav_menu
	public static function render_fancy_nav_menu ( $widget_content, $element ) {

		if ( 'nav--menu' === $element->get_name() ) {
			$widget_content = '<p>Hola menda</p>';
		}
		if ( 'nav-menu' === $element->get_name() ) {

			$available_menus = self::get_available_menus();

			if ( ! $available_menus ) {
				return $widget_content;
			}
			$settings = $element->get_active_settings();

			$args = [
				'echo' => false,
				'menu' => $settings['menu'],
				'menu_class' => 'elementor-nav-menu',
				'menu_id' => 'menu-' . self::get_nav_menu_index() . '-' . $element->get_id(),
				'fallback_cb' => '__return_empty_string',
				'container' => '',
			];

			if ( 'vertical' === $settings['layout'] ) {
				$args['menu_class'] .= ' sm-vertical';
			}
			self::$activate_root_menu = $settings['activate_root_menu'];
			// Add custom filter to handle Nav Menu HTML output.
			add_filter( 'nav_menu_link_attributes', [ $element, 'handle_link_tabindex' ], 10, 4 );
			add_filter( 'nav_menu_link_attributes', [ __CLASS__, 'handle_link_attributes' ], 10, 4 );
			add_filter( 'nav_menu_submenu_css_class', [ $element, 'handle_sub_menu_classes' ] );
			add_filter( 'nav_menu_item_title', [ __CLASS__, 'handle_item_title'], 10, 4);
			add_filter( 'nav_menu_item_id', '__return_empty_string' );

			// General Menu.
			$menu_html = wp_nav_menu( $args );


			// Dropdown Menu.
			$args['menu_id'] = 'menu-' . self::get_nav_menu_index() . '-' . $element->get_id();
			$args['menu_type'] = 'dropdown';
			$dropdown_menu_html = wp_nav_menu( $args );

			// Remove all of our custom filters so as not to affect other menus.
			remove_filter( 'nav_menu_link_attributes', [ $element, 'handle_link_tabindex' ] );
			remove_filter( 'nav_menu_link_attributes', [ __CLASS__, 'handle_data_text' ] );
			remove_filter( 'nav_menu_submenu_css_class', [ $element, 'handle_sub_menu_classes' ] );
			remove_filter( 'nav_menu_item_title', [ __CLASS__, 'handle_item_title'] );
			remove_filter( 'nav_menu_item_id', '__return_empty_string' );

			if ( empty( $menu_html ) ) {
				return;
			}

			$element->add_render_attribute( 'menu-toggle', [
				'class' => 'elementor-menu-toggle',
				'role' => 'button',
				'tabindex' => '0',
				'aria-label' => esc_html__( 'Menu Toggle', 'elementor-pro' ),
				'aria-expanded' => 'false',
			] );

			if ( Plugin::elementor()->editor->is_edit_mode() ) {
				$element->add_render_attribute( 'menu-toggle', [
					'class' => 'elementor-clickable',
				] );
			}

			$is_migrated = isset( $settings['__fa4_migrated']['submenu_icon'] );

			$element->add_render_attribute( 'main-menu', [
				'migration_allowed' => Icons_Manager::is_migration_allowed() ? '1' : '0',
				'migrated' => $is_migrated ? '1' : '0',
				// Accessibility
				'role' => 'navigation',
			] );

			if ( 'dropdown' !== $settings['layout'] ) :
				$element->add_render_attribute( 'main-menu', 'class', [
					'elementor-nav-menu--main',
					'elementor-nav-menu__container',
					'elementor-nav-menu--layout-' . $settings['layout'],
					'elemendas-fancy-nav-menu',
				] );

				if ( $settings['pointer'] ) :
					$element->add_render_attribute( 'main-menu', 'class', 'e--pointer-' . $settings['pointer'] );

					foreach ( $settings as $key => $value ) :
						if ( 0 === strpos( $key, 'animation' ) && $value ) :
							$element->add_render_attribute( 'main-menu', 'class', 'e--animation-' . $value );

							break;
						endif;
					endforeach;
				endif;
				// BEGIN main menu
				$widget_content  = '<nav ' . $element->get_render_attribute_string( 'main-menu' ) .'>';
				$widget_content .= $menu_html .'</nav>';
				// END main menu
				$widget_content .= '<div ' . $element->get_render_attribute_string( 'menu-toggle' ) .'>';
			else :
				$widget_content = '<div ' . $element->get_render_attribute_string( 'menu-toggle' ) .'>';
			endif;

			$toggle_icon_hover_animation = ! empty( $settings['toggle_icon_hover_animation'] )
			? ' elementor-animation-' . $settings['toggle_icon_hover_animation']
			: '';

			$open_class = 'elementor-menu-toggle__icon--open' . $toggle_icon_hover_animation;
			$close_class = 'elementor-menu-toggle__icon--close' . $toggle_icon_hover_animation;

			$normal_icon = ! empty( $settings['toggle_icon_normal']['value'] )
				? $settings['toggle_icon_normal']
				: [
					'library' => 'eicons',
					'value' => 'eicon-menu-bar',
				];
			if ( 'svg' === $normal_icon['library'] ) {
				$widget_content .= '<span class="' . esc_attr( $open_class ) . '">';
			}

			if (!empty( $normal_icon['library'] ) ) {
				if ( 'svg' === $normal_icon['library'] ) {
					$widget_content .= Icons_Manager::render_uploaded_svg_icon( $normal_icon['value'] );
				} else {
					$widget_content .= Icons_Manager::render_font_icon( $normal_icon,
																		[
																			'aria-hidden' => 'true',
																			'role' => 'presentation',
																			'class' => $open_class,
																		],
																		'i'
																		);
				}
			}

			if ( 'svg' === $normal_icon['library'] ) {
				$widget_content .= '</span>';
			}



			$active_icon = ! empty( $settings['toggle_icon_active']['value'] )
				? $settings['toggle_icon_active']
				: [
					'library' => 'eicons',
					'value' => 'eicon-close',
				];

			if ( 'svg' === $active_icon['library'] ) {
				$widget_content .= '<span class="' . esc_attr( $close_class ) . '">';
			}

			if (!empty( $active_icon['library'] ) ) {
				if ( 'svg' === $active_icon['library'] ) {
					$widget_content .= Icons_Manager::render_uploaded_svg_icon( $active_icon['value'] );
				} else {
					$widget_content .= Icons_Manager::render_font_icon( $active_icon,
																		[
																			'aria-hidden' => 'true',
																			'role' => 'presentation',
																			'class' => $close_class,
																		],
																		'i'
																		);
				}
			}

			if ( 'svg' === $active_icon['library'] ) {
				$widget_content .= '</span>';
			}
			$widget_content .= '<span class="elementor-screen-only">' . esc_html__( 'Menu', 'elementor-pro' ) .'</span></div>';

			// BEGIN dropdown menu
			$widget_content .= '<nav class="elementor-nav-menu--dropdown elementor-nav-menu__container elemendas-fancy-nav-menu" role="navigation" aria-hidden="true">';
			$widget_content .= $dropdown_menu_html .'</nav>';
			// END dropdown menu
		}
		return $widget_content;
	} // END render_fancy_nav_menu

	public static function handle_item_title( $title, $item, $args, $depth ) {
		$icon = get_field('elm_icono', $item);
		if ($icon) :
//			$iconURL = esc_url(ELM_PLUGIN_URL . 'includes/acf/assets/img/svg/' . $icon);
			$iconPATH = trailingslashit( wp_upload_dir()['basedir']) . 'elemendas-svg-icons/' . $icon;
/*			$fixedIcon = get_field('elm_fixicon', $item);
			if (in_array('fixed', $fixedIcon)):
				$iconTag = '<object data="'.$iconURL.'"></object>';
			else :
*/				$iconTag = file_get_contents($iconPATH);
				$iconTag = str_replace('<svg','<svg width="1em" height="1em"',$iconTag);
//			endif;
			$title = $iconTag .= $title;
		endif;
		return $title;
	}

	public static function handle_link_attributes( $atts, $item, $args, $depth ) {
		// classes
		$classes = $depth ? 'elementor-sub-item' : 'elementor-item';
		$is_anchor = false !== strpos( $atts['href'], '#' );
		// is_anchor are items that have no link (yes it's crazy), typically parent nodes with submenus.

		if ( ! $is_anchor && in_array( 'current-menu-item', $item->classes ) ) {
			$classes .= ' elementor-item-active';
		}


		if ( $is_anchor ) {
			$classes .= ' elementor-item-anchor'; // menu item with no link
		}

		if ( self::$activate_root_menu && !$depth && in_array( 'current-menu-ancestor', $item->classes ) ) {
			$classes .= ' elementor-item-active';
		}

		if ( empty( $atts['class'] ) ) {
			$atts['class'] = $classes;
		} else {
			$atts['class'] .= ' ' . $classes;
		}



		// style (colors)
		$style = '';
		$color = get_field ('elm_normal_color', $item);
		if ($color) $style .= '--elm-normal-color: '.$color.'; ';

		$color = get_field ('elm_hover_color', $item);
		if ($color) $style .= '--elm-hover-color: '.$color.'; ';

		$color = get_field ('elm_active_color', $item);
		if ($color) $style .= '--elm-active-color: '.$color.'; ';

		if ('' !== $style) $atts['style'] = $style;

		// data-text
		$atts['data-text'] = $item->title;
		return $atts;
	}

} //END class Search_Results

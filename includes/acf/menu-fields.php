<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'elemendas_ext_wp_menus',
	'title' => 'Extended WP Menus',
	'fields' => array(
		array(
			'key' => 'elm_ext_wp_menus_icon',
			'label' => esc_html__('Icon', 'elemendas-addons' ),
			'name' => 'elm_icono',
			'type' => 'icon-picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'initial_value' => '',
		),
		array(
			'key' => 'elm_ext_wp_menus_icon_fixed',
			'label' => esc_html__('Fixed color icon', 'elemendas-addons' ),
			'name' => 'elm_fixicon',
			'type' => 'checkbox',
			'instructions' => esc_html__('If checked, the icon will not change color with the text. Recommended only for colored icons.', 'elemendas-addons' ),
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'fixed' => esc_html_x('fix','Fixed color icon', 'elemendas-addons' ),
			),
			'allow_custom' => 0,
			'default_value' => array(
			),
			'layout' => 'vertical',
			'toggle' => 0,
			'return_format' => 'value',
			'save_custom' => 0,
		),
			array(
			'key' => 'elm_ext_wp_menus_normal_color',
			'label' => esc_html__('Normal Color', 'elemendas-addons' ),
			'name' => 'elm_normal_color',
			'type' => 'color_picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'enable_opacity' => 1,
			'return_format' => 'string',
		),
		array(
			'key' => 'elm_ext_wp_menus_hover_color',
			'label' => esc_html__('Hover Color', 'elemendas-addons' ),
			'name' => 'elm_hover_color',
			'type' => 'color_picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'enable_opacity' => 1,
			'return_format' => 'string',
		),
		array(
			'key' => 'elm_ext_wp_menus_active_color',
			'label' => esc_html__('Active Color', 'elemendas-addons' ),
			'name' => 'elm_active_color',
			'type' => 'color_picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'enable_opacity' => 1,
			'return_format' => 'string',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'nav_menu_item',
				'operator' => '==',
				'value' => 'all',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 1,
));

endif;

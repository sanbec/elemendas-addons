<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'elemendas_ext_wp_menus',
	'title' => 'Extended WP Menus',
	'fields' => array(
		array(
			'key' => 'elemendas_ext_wp_menus_icon',
			'label' => 'Icono',
			'name' => 'icono',
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
			'key' => 'elemendas_ext_wp_menus_color',
			'label' => 'Color',
			'name' => 'color',
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

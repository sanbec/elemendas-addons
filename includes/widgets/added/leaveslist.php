<?php
namespace Elemendas_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class Leaves_List extends \Elementor\Widget_Base {

	public function get_name() {
		return 'leaveslist';
	}

	public function get_title() {
		return esc_html_x( 'Leaves List', 'Widget Name', 'elemendas-addons' );
	}

	public function get_icon() {
		return 'elm elm-plant';
	}
	public function get_custom_help_url() {
		return 'https://elemendas.com/widgets-y-extensiones/';
	}

	public function get_categories() {
		return ['general'];
	}

	public function get_keywords() {
		return [ 'Leaf', 'Leaves', 'List' ];
	}

	public function get_style_depends() {
		wp_register_style( 'leaveslist-style', plugins_url( 'assets/css/leaveslist.css', __FILE__ ), false, ELEMENDAS_ADDONS_VERSION );
		return [
			'leaveslist-style',
		];
	}
	/**
	 * Widget constructor.
	 *
	 * Initializing the widget class.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @throws \Exception If arguments are missing when initializing a full widget
	 *                   instance.
	 *
	 * @param array      $data Widget data. Default is an empty array.
	 * @param array|null $args Optional. Widget default arguments. Default is null.
	 */


	// BEGIN register_controls
	protected function register_controls() {


/*********************
 * BEGIN Content Tab *
 *********************/

        $this->start_controls_section(
			'section_leaveslist_content',
			[
				'label' => esc_html_x( 'Leaves List', 'Editor content section title', 'elemendas-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$leaf = new \Elementor\Repeater();
		$leaf->add_control(
			'leaf_text',
			[
				'label' => esc_html__( 'Text', 'elemendas-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Leaf', 'elemendas-addons' ),
				'default' => esc_html__( 'Leaf', 'elemendas-addons' ),
			]
		);
		$leaf->add_control(
			'leaf_link',
			[
				'label' => esc_html__( 'Link', 'elemendas-addons' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'elemendas-addons' ),
			]
		);
		$this->add_control(
			'leaves',
			[
				'label' => esc_html__( 'Leaves', 'elemendas-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $leaf->get_controls(),
				'default' => [
					[
						'leaf_text' => esc_html__( 'Leaf #1', 'elemendas-addons' ),
					],
					[
						'leaf_text' => esc_html__( 'Leaf #2', 'elemendas-addons' ),
					],
					[
						'leaf_text' => esc_html__( 'Leaf #3', 'elemendas-addons' ),
					],
				],
				'title_field' => '{{{ leaf_text }}}',
			]
		);
		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'margin-right' => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'margin' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'margin-left' => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'margin',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} ul.ul-plant' => 'margin: unset; {{VALUE}}: auto;',
				],
			]
		);


		$this->end_controls_section();
		// END Content Tab. Note that this section will be followed by the content title section
		// inherited from the heading widget. (See the end of this function)

/*******************
 * BEGIN Style Tab *
 *******************/

		$this->start_controls_section(
			'section_leaveslist_style',
			[
				'label' => esc_html__( 'Plant', 'elemendas-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// BEGIN Leaveslist Controls

		$this->add_control(
			'leaf_length',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Leaf length', 'elemendas-addons' ),
   				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 80,
						'max' => 250,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} ul.ul-plant' => '--leaf-length: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'leaf_width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Leaf width', 'elemendas-addons' ),
   				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 50,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 70,
				],
				'selectors' => [
					'{{WRAPPER}} ul.ul-plant' => '--leaf-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'leaf_gap',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Leaf gap', 'elemendas-addons' ),
   				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} ul.ul-plant' => '--col-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'stem_width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Stem width', 'elemendas-addons' ),
   				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 4,
						'max' => 15,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} ul.ul-plant' => '--stem-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'plant_color',
			[
				'label' => esc_html__( 'Plant color', 'elemendas-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#2c3',
				'selectors' => [
					'{{WRAPPER}} ul.ul-plant' => '--leaf-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
		// END Plant style section

		// BEGIN Text style section
		$this->start_controls_section(
			'section_text_style',
			[
				'label' => esc_html__( 'Text', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// Text Color

		$this->add_control(
			'leaves_text_color',
			[
				//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
				'label' => esc_html__( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.ul-plant > li span' => 'color: {{VALUE}};',
				],
			]
		);

		// Background Color

		$this->add_control(
			'leaves_text_bgcolor',
			[
				//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
				'label' => esc_html__( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.ul-plant > li span' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'leaves_text_typography',
				'selector' => '{{WRAPPER}} ul.ul-plant > li span',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'leaves_text_stroke',
				'selector' => '{{WRAPPER}} ul.ul-plant > li span',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'leaves_text_shadow',
				'selector' => '{{WRAPPER}} ul.ul-plant > li span',
			]
		);
		// END Text Style Search Results Controls





		// END Leaveslist Controls

		$this->end_controls_section();
	// END Style section
	} // END protected function register_controls



	protected function render() {
		$settings = $this->get_settings_for_display();
        ?>
		<ul class="ul-plant">
		<?php foreach ( $settings['leaves'] as $index => $item ) : ?>
			<li>
				<?php
				if ( ! $item['leaf_link']['url'] ) {
					echo '<span>'.$item['leaf_text'].'</span>';
				} else {
					?><a href="<?php echo esc_url( $item['leaf_link']['url'] ); ?>"><span><?php echo $item['leaf_text']; ?></span></a><?php
				}
				?>
			</li>
		<?php endforeach; ?>
		</ul>
		<?php
	}  // protected function render

	protected function content_template() {
		?>
		<ul class="ul-plant">
		<#
		if ( settings.leaves ) {
			_.each( settings.leaves, function( item, index ) {
			#>
			<li>
				<# if ( item.leaf_link && item.leaf_link.url ) { #>
					<a href="{{{ item.leaf_link.url }}}"><span>{{{ item.leaf_text }}}</span></a>
				<# } else { #>
				<span>{{{ item.leaf_text }}}</span>
				<# } #>
			</li>
			<#
			} );
		}
		#>
		</ul>
		<?php
    }  //END protected function content_template

} //END class Leaves_List

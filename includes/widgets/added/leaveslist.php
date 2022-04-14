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
		return 'fab fa-envira';
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
				'label' => esc_html_x( 'Leaves List', 'Widget Name', 'elemendas-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
        $this->add_control(
			'leaves',
			[
				'label' => esc_html__( 'Leaves List', 'elemendas-addons' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'text',
						'label' => esc_html__( 'Text', 'elemendas-addons' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'placeholder' => esc_html__( 'Leaf', 'elemendas-addons' ),
						'default' => esc_html__( 'Leaf', 'elemendas-addons' ),
					],
				],
				'default' => [
					[
						'text' => esc_html__( 'Leaf #1', 'elemendas-addons' ),
					],
					[
						'text' => esc_html__( 'Leaf #2', 'elemendas-addons' ),
					],
					[
						'text' => esc_html__( 'Leaf #3', 'elemendas-addons' ),
					],
					[
						'text' => esc_html__( 'Leaf #4', 'elemendas-addons' ),
					],
									[
						'text' => esc_html__( 'Leaf #5', 'elemendas-addons' ),
					],
],
				'title_field' => '{{{ text }}}',
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
				'label' => esc_html__( 'Search string highlighting', 'elemendas-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// BEGIN Leaveslist Controls



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
                <span><?php echo $item['text'];?></span>
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
                <span>{{{ item.text }}}</span>
			</li>
			<#
			} );
		}
		#>
		</ul>
		<?php
    }  //END protected function content_template

} //END class Leaves_List

<?php
namespace Elemendas_Addons;

use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor image carousel widget.
 *
 * Elementor widget that displays a set of images in a rotating carousel or
 * slider.
 *
 * @since 1.0.0
 */
class Carousel_3D extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image carousel widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'elmendas-carousel3D';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image carousel widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Carousel 3D', 'elemendas-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image carousel widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'elm elm-gallery3D';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'image', 'photo', 'visual', 'carousel', 'slider', '3D'];
	}

	public function get_style_depends() {
		wp_register_style( 'carousel3D-style', plugins_url( 'assets/css/carousel3D.css', __FILE__ ), false, ELEMENDAS_ADDONS_VERSION );
		return [
			'carousel3D-style',
		];
	}

	/**
	 * Register image carousel widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.1.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_carousel_images',
			[
				'label' => esc_html__( 'Carousel images', 'elemendas-addons' ),
			]
		);

		$this->add_control(
			'carousel',
			[
				'label' => esc_html__( 'Add Images', 'elementor' ),
				'type' => Controls_Manager::GALLERY,
				'default' => [
					[
						'id' => 0,
						'url' =>  plugins_url('elementor/assets/images/placeholder.png','elementor')
					],
					[
						'id' => 0,
						'url' =>  plugins_url('elementor/assets/images/placeholder.png','elementor')
					],
					[
						'id' => 0,
						'url' =>  plugins_url('elementor/assets/images/placeholder.png','elementor')
					],
					[
						'id' => 0,
						'url' =>  plugins_url('elementor/assets/images/placeholder.png','elementor')
					],
					[
						'id' => 0,
						'url' =>  plugins_url('elementor/assets/images/placeholder.png','elementor')
					],
				],
				'show_label' => false,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'image_width',
			[
				'label' => esc_html__( 'Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
   				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 400,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 300,
				],
				'selectors' => [
					'{{WRAPPER}} .elmadd-3Dcard' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_height',
			[
				'label' => esc_html__( 'Height', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
   				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 400,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 300,
				],
				'selectors' => [
					'{{WRAPPER}} .elmadd-3Dcard' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_carousel_settings',
			[
				'label' => esc_html__( 'Carousel Settings', 'elemendas-addons' ),
			]
		);


		$this->add_control(
			'carousel_width',
			[
				'label' => esc_html__( 'Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
   				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 33,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .elmadd-carousel3D' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'carousel_height',
			[
				'label' => esc_html__( 'Height', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
   				'size_units' => ['vh'],
				'range' => [
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'vh',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .elmadd-carousel3D' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'radius',
			[
				'label' => esc_html__( 'Carousel Radius', 'elemendas-addons' ),
				'type' => Controls_Manager::SLIDER,
   				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 400,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 300,
				],
				'selectors' => [
					'{{WRAPPER}} .elmadd-carousel3D' => '--z-translation: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'perspective',
			[
				'label' => esc_html__( 'Perspective', 'elemendas-addons' ),
				'type' => Controls_Manager::SLIDER,
   				'size_units' => ['vw'],
				'range' => [
					'vw' => [
						'min' => 40,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'vw',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .elmadd-carousel3D' => 'perspective: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'paused',
				'label_on' => esc_html__( 'Yes', 'elemendas-addons' ),
				'label_off' => esc_html__( 'No', 'elemendas-addons' ),
				'selectors' => [
					'{{WRAPPER}} .elmadd-3Dcards-div:hover' => 'animation-play-state: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'Rotation Speed (rpm)', 'elemendas-addons' ),
				'type' => Controls_Manager::SLIDER,
   				'size_units' => ['rpm'],
				'range' => [
					'rpm' => [
						'min' => 3,
						'max' => 15,
					],
				],
				'default' => [
					'unit' => 'rpm',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .elmadd-carousel3D' => '--rpm: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'direction',
			[
				'label' => esc_html__( 'Direction of rotation', 'elemendas-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'reverse' => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon' => ' eicon-arrow-left',
					],
					'normal' => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon' => ' eicon-arrow-right',
					],
				],
				'default' => 'normal',
				'selectors' => [
					'{{WRAPPER}} .elmadd-3Dcards-div' => 'animation-direction: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Image border', 'elemendas-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'border_style',
			[
				'label' => esc_html__( 'Style', 'elementor' ),
				'type' => 'border-style',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elmadd-3Dcard' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_border_width',
			[
				'label' => esc_html__( 'Width', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elmadd-3Dcard' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'image_border_style!' => '',
				],
			]
		);
		$this->add_control(
			'image_border_color',
			[
				//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elmadd-3Dcard' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'image_border_style!' => '',
				],
			]
		);
		$this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elmadd-3Dcard' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render image carousel widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['carousel'] ) ) {
			return;
		}

		$slides = [];
		$slides_count = count( $settings['carousel'] );
		$rotateInc = 360 / $slides_count;
		$rotateY = 0;

		foreach ( $settings['carousel'] as $image) {
			$image_url = esc_attr( $image['url'] );
			$image_html = '<div class="elmadd-3Dcard" style="background-image: url(\'' . esc_attr( $image_url ) . '\'); transform: rotateY('.$rotateY.'deg) translateZ(var(--z-translation));" /></div>';
			$rotateY += $rotateInc;

			$slides[] = $image_html;
		}

		if ( empty( $slides ) ) {
			return;
		}

		$this->add_render_attribute( [
			'carousel' => [
				'class' => 'elmadd-carousel3D',
			],
			'cards-div' => [
				'class' => 'elmadd-3Dcards-div',
			],
		] );

		?>
		<div <?php $this->print_render_attribute_string( 'carousel' ); ?>>
			<div <?php $this->print_render_attribute_string( 'cards-div' ); ?>>
				<?php // PHPCS - $slides contains the slides content, all the relevent content is escaped above. ?>
				<?php echo implode( '', $slides ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
		<?php
	}
}

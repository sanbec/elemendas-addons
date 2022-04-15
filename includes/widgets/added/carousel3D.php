<?php
namespace Elemendas_Addons;

use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
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
			'section_image_carousel',
			[
				'label' => esc_html__( 'Image Carousel', 'elemendas-addons' ),
			]
		);

		$this->add_control(
			'carousel',
			[
				'label' => esc_html__( 'Add Images', 'elementor' ),
				'type' => Controls_Manager::GALLERY,
				'default' => [],
				'show_label' => false,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'separator' => 'none',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => esc_html__( 'Additional Options', 'elementor' ),
			]
		);


		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => esc_html__( 'Yes', 'elementor' ),
					'no' => esc_html__( 'No', 'elementor' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => esc_html__( 'Yes', 'elementor' ),
					'no' => esc_html__( 'No', 'elementor' ),
				],
				'condition' => [
					'autoplay' => 'yes',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pause_on_interaction',
			[
				'label' => esc_html__( 'Pause on Interaction', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => esc_html__( 'Yes', 'elementor' ),
					'no' => esc_html__( 'No', 'elementor' ),
				],
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'Autoplay Speed', 'elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
				'condition' => [
					'autoplay' => 'yes',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		// Loop requires a re-render so no 'render_type = none'
		$this->add_control(
			'infinite',
			[
				'label' => esc_html__( 'Infinite Loop', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => esc_html__( 'Yes', 'elementor' ),
					'no' => esc_html__( 'No', 'elementor' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => esc_html__( 'Animation Speed', 'elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'direction',
			[
				'label' => esc_html__( 'Direction', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'ltr',
				'options' => [
					'ltr' => esc_html__( 'Left', 'elementor' ),
					'rtl' => esc_html__( 'Right', 'elementor' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Image', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'gallery_vertical_align',
			[
				'label' => esc_html__( 'Vertical Align', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-wrapper' => 'display: flex; align-items: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_spacing',
			[
				'label' => esc_html__( 'Spacing', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Default', 'elementor' ),
					'custom' => esc_html__( 'Custom', 'elementor' ),
				],
				'default' => '',
			]
		);

		$this->add_control(
			'image_spacing_custom',
			[
				'label' => esc_html__( 'Image Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 20,
				],
				'show_label' => false,
				'condition' => [
					'image_spacing' => 'custom',
				],
				'frontend_available' => true,
				'render_type' => 'none',
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .swiper-slide-image',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .swiper-slide-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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

		foreach ( $settings['carousel'] as $index => $attachment ) {
			$image_url = Group_Control_Image_Size::get_attachment_image_src( $attachment['id'], 'thumbnail', $settings );

			if ( ! $image_url && isset( $attachment['url'] ) ) {
				$image_url = $attachment['url'];
			}

			$image_html = '<div class="elmadd-3Dcard card1" style="background-image: url(\'' . esc_attr( $image_url ) . '\'); transform: rotateY('.$rotateY.'deg) translateZ(var(--z-translation));" /></div>';
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
			'cards' => [
				'class' => 'elmadd-3Dcards-div',
			],
		] );

		?>
		<div <?php $this->print_render_attribute_string( 'carousel' ); ?>>
			<div <?php $this->print_render_attribute_string( 'cards' ); ?>>
				<?php // PHPCS - $slides contains the slides content, all the relevent content is escaped above. ?>
				<?php echo implode( '', $slides ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
		<?php
	}
}

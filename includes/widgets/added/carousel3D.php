<?php
namespace Elemendas\Addons\Widgets;

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
		return 'elemendas-carousel3D';
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
		return 'elm elm-carousel3D';
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


		// BEGIN TAB_CONTENT
		// BEGIN TAB_CONTENT Carousel images section
		$this->start_controls_section(
			'section_carousel_images',
			[
				'label' => esc_html__( 'Carousel images', 'elemendas-addons' ),
			]
		);

		function check_url($url) {
			$headers = @get_headers( $url);
			$headers = (is_array($headers)) ? implode( "\n ", $headers) : $headers;
		return (bool)preg_match('#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers);
		}

		$img_url[]='https://placekitten.com/400';
		$img_url[]='https://placekitten.com/401';
		$img_url[]='https://placekitten.com/402';
		$img_url[]='https://placekitten.com/403';
		$img_url[]='https://placekitten.com/404';


		if (!check_url($img_url[0])) {
			foreach ($img_url as $img) {
				$img=plugins_url('elementor/assets/images/placeholder.png','elementor');
			}
		}


		$this->add_control(
			'carousel',
			[
				'label' => esc_html__( 'Add Images', 'elementor' ),
				'type' => Controls_Manager::GALLERY,
				'default' => [
					[
						'id' => 0,
						'url' => $img_url[0]
					],
					[
						'id' => 0,
						'url' => $img_url[1]
					],
					[
						'id' => 0,
						'url' => $img_url[2]
					],
					[
						'id' => 0,
						'url' => $img_url[3]
					],
					[
						'id' => 0,
						'url' => $img_url[4]
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
		// END TAB_CONTENT Carousel images section

		// BEGIN TAB_CONTENT Carousel settings section
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
				'separator' => 'before',
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
				'separator' => 'before',
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
				'toggle' => false,
			]
		);

		$this->add_control(
			'open_lightbox',
			[
				'label' => esc_html__( 'Lightbox', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'label_on' => esc_html__( 'Yes', 'elemendas-addons' ),
				'label_off' => esc_html__( 'No', 'elemendas-addons' ),
				'separator' => 'before',
				'description' =>  __('Note: The lightbox will not work with the default kitten images. You must select your images from the media library or upload new ones.', 'elemendas-addons'),
			]
		);

		$this->add_control(
			'show_reflect',
			[
				'label' => esc_html__( 'Show image reflect', 'elemendas-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_on' => esc_html__( 'Yes', 'elemendas-addons' ),
				'label_off' => esc_html__( 'No', 'elemendas-addons' ),
				'selectors' => [
					'{{WRAPPER}} .elmadd-3Dcard' => '  -webkit-box-reflect: below 10px linear-gradient(rgba(255, 255, 255, 0.226), rgba(255, 255, 255, 0.151));',
				],
				'separator' => 'before',
				'description' =>  __('Note: This feature is not a CSS standard and will not work in all browsers.', 'elemendas-addons'),

			]
		);

		$this->end_controls_section();
		// END TAB_CONTENT Carousel settings section
		// END TAB_CONTENT

		// BEGIN TAB_STYLE
		// BEGIN TAB_STYLE Image section
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Image', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// BEGIN TAB_STYLE Image Tabs
		$this->start_controls_tabs( 'image_tabs' );

		// BEGIN TAB_STYLE Image normal tab
		$this->start_controls_tab(
			'image_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor' ),
			]
		);

		$this->add_control(
			'image_border',
			[
				'label' => esc_html__( 'Border', 'elemendas-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'image_border_style',
			[
				'label' => esc_html__( 'Style', 'elementor' ),
				'type' => 'border-style',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elmadd-3Dimage' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .elmadd-3Dimage' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .elmadd-3Dimage' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'image_border_style!' => '',
				],
			]
		);
		$this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Radius', 'elemendas-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elmadd-3Dimage' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} .elmadd-3Dimage',
			]
		);

		$this->end_controls_tab(); // Image normal
		// END TAB_STYLE Image normal tab

		// BEGIN TAB_STYLE Image hover tab
		$this->start_controls_tab(
			'image_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor' ),
			]
		);
		$this->add_control(
			'image_border_hover',
			[
				'label' => esc_html__( 'Border', 'elemendas-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'image_border_style_hover',
			[
				'label' => esc_html__( 'Style', 'elementor' ),
				'type' => 'border-style',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elmadd-3Dimage:hover' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_border_width_hover',
			[
				'label' => esc_html__( 'Width', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elmadd-3Dimage:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'image_border_style_hover!' => '',
				],
			]
		);
		$this->add_control(
			'image_border_color_hover',
			[
				//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elmadd-3Dimage:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'image_border_style_hover!' => '',
				],
			]
		);
		$this->add_control(
			'image_border_radius_hover',
			[
				'label' => esc_html__( 'Radius', 'elemendas-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elmadd-3Dimage:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters_hover',
				'selector' => '{{WRAPPER}} .elmadd-3Dimage:hover',
			]
		);

		$this->add_control(
			'image_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'elemendas-addons' ),
				'label_block' => true,
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab(); // image hover
		// END TAB_STYLE Image hover tab

		$this->end_controls_tabs();
		// END TAB_STYLE image tabs


		$this->end_controls_section();
		// END TAB_STYLE Image section
		// END TAB_STYLE

	}

	/**
	 * Render image carousel widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() { //BEGIN render()
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['carousel'] ) ) {
			return;
		}

		$slides = [];
		$slides_count = count( $settings['carousel'] );
		$rotateInc = 360 / $slides_count;
		$rotateY = 0;

		if ($settings['image_hover_animation']) {
			$animation = ' elementor-animation-'.$settings['image_hover_animation'];
		} else {
			$animation = '';
		}

		foreach ( $settings['carousel'] as $index => $image) {
			$image_url = esc_attr( $image['url'] );
			$image_html = '<figure class="elmadd-3Dcard" style="transform: rotateY('.$rotateY.'deg) translateZ(var(--z-translation));" />';
			$image_html .= '<div class="elmadd-3Dimage'.$animation.'" style="background-image: url(\'' . esc_attr( $image_url ) . '\');" />';
			$lightbox = 'yes' === $settings['open_lightbox'];
			if ( $lightbox ) {
				$link_key = 'link_' . $index;
				$this->add_lightbox_data_attributes( $link_key, $image['id'], $lightbox, $this->get_id() );

				if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
					$this->add_render_attribute( $link_key, ['class' => 'elementor-clickable elmadd-lightbox-link', ] );
				} else {
					$this->add_render_attribute( $link_key, ['class' => 'elmadd-lightbox-link', ] );
				}
				$this->add_link_attributes( $link_key, ['url' => $image['url']] );
				$image_html .= '<a ' . $this->get_render_attribute_string( $link_key ) . '></a>';
			}
			$image_html .= '</div></figure>';
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
	} // END render()


	public function content_template() {
		?>
		<#
		if ( settings.carousel && settings.carousel.length > 0  ) {
            var slides = [];
            var slides_count = settings.carousel.length ;
			var rotateInc = 360 / slides_count;
			var rotateY = 0;
			var animation = '';

			if (settings.image_hover_animation) {
				animation = ' elementor-animation-'+ settings.image_hover_animation;
			}

			_.each( settings.carousel, function( image, index ) {

				let image_html = '<figure class="elmadd-3Dcard" style="transform: rotateY('+rotateY+'deg) translateZ(var(--z-translation));" />';
				image_html += '<div class="elmadd-3Dimage'+animation+'" style="background-image: url(\''+image.url+'\');" />';
				let lightbox = ('yes' === settings.open_lightbox);
				if ( lightbox ) {
					image_html += '<a class="elementor-clickable elmadd-lightbox-link" data-elementor-open-lightbox="'+settings.open_lightbox+'" href="'+image.url+'"></a>';
				}
				image_html += '</div></figure>';
				rotateY += rotateInc;

				slides.push (image_html);
			} );

			view.addRenderAttribute( 'carousel', 'class', 'elmadd-carousel3D' );
			view.addRenderAttribute( 'cards-div', 'class', 'elmadd-3Dcards-div' );

		#>

		<div {{{ view.getRenderAttributeString( 'carousel' ) }}}>
			<div {{{ view.getRenderAttributeString( 'cards-div' ) }}}>
				{{{slides.join("")}}}
			</div>
		</div>
		<#
		} else { #>
				<div class="elementor-panel-alert elementor-panel-alert-info elemendas-notice">
					<i aria-hidden="true" class=" eicon-slider-album"></i>
					<span><?php
						// translators: %s: widget name
						printf( esc_html__('The carousel has no images, please select at least one', 'elemendas-addons')
								);?>
					</span>
				</div>
		<# } #>
		<?php
	}
}

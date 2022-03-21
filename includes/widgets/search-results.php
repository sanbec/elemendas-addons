<?php
namespace Elemendas_Addon;

use Elementor\Controls_Manager;

class Search_Results extends \Elementor\Widget_Heading {

	public function get_name() {
		return 'search-results_widget';
	}

	public function get_title() {
		return esc_html__( 'Search Results', 'elemendas-addon' );
	}

	public function get_icon() {
		return 'eicon-search-results';
	}
	
	public function get_custom_help_url() {
		return 'https://elemendas.com/widgets-y-extensiones/';
	}

	public function get_categories() {
		return ['theme-elements-archive'];
	}

	public function get_keywords() {
		return [ 'Search', 'Results' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section_title',
			[
				'label' => esc_html__( 'Content', 'elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_results_plural',
			[
				'label' => esc_html__( 'Multiple results', 'elemendas-addon' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 2,
				'default' => __( 'Here are the {{result-number}} posts containing {{search-string}}', 'elemendas-addon' ),
				'description' => __('Shown when there are more than one post fonnd').'<ul><li>'.__('Use {{result-number}} to show the post found number', 'elemendas-addon' ).'</li><li>'.__('Use {{search-string}} to show the search string', 'elemendas-addon' ).'</li></ul>',
			]
		);
		$this->add_control(
			'show_results_single',
			[
				'label' => esc_html__( 'Only one result', 'elemendas-addon' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 2,
				'default' => __( 'Here is the unique post containing {{search-string}}', 'elemendas-addon' ),
				'description' =>  __('Shown when there are more than one post fonnd'),
			]
		);
		$this->add_control(
			'show_results_none',
			[
				'label' => esc_html__( 'No results found', 'elemendas-addon' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 2,
				'default' => __( 'There are no posts containing {{search-string}}', 'elemendas-addon' ),
				'description' =>  __('Shown when there are no posts found'),
			]
		);

		$this->add_control(
			'size',
			[
				'label' => esc_html__( 'Size', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'elementor' ),
					'small' => esc_html__( 'Small', 'elementor' ),
					'medium' => esc_html__( 'Medium', 'elementor' ),
					'large' => esc_html__( 'Large', 'elementor' ),
					'xl' => esc_html__( 'XL', 'elementor' ),
					'xxl' => esc_html__( 'XXL', 'elementor' ),
				],
			]
		);

		$this->add_control(
			'header_size',
			[
				'label' => esc_html__( 'HTML Tag', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);
//*/
		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start

		$this->start_controls_section(
			'section_results_style',
			[
				'label' => esc_html__( 'Results message', 'elemendas-addon' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'results_color',
			[
				'label' => esc_html__( 'Color', 'elemendas-addon' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elemendas-results-message' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_search_string_style',
			[
				'label' => esc_html__( 'Search String', 'elemendas-addon' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'search_string_color',
			[
				'label' => esc_html__( 'Color', 'elemendas-addon' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elemendas-search-terms' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab End

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		if ( '' === $settings['show_results_plural'] ) {
			return;
		}
		if ( '' === $settings['show_results_single'] ) {
			$settings['show_results_single'] = $settings['show_results_plural'];
		}
		if ( '' === $settings['show_results_none'] ) {
			$settings['show_results_none'] = $settings['show_results_plural'];
		}
		
		$this->add_render_attribute( 'title', 'class', 'elementor-heading-title' );

		$search_query = get_search_query();
		if (is_null($search_query) || $search_query == '') return;
		$searchall = new \WP_Query("s=$search_query&showposts=-1");
		$post_count = $searchall->post_count;
		switch ($post_count) {
			case 0:
				$render_text = $settings['show_results_none'];
				break;
			case 1:
				$render_text = $settings['show_results_single'];
				break;
			default:
				$render_text = $settings['show_results_plural'];
				break;
		}

				

		$render_text = str_replace ("{{search-string}}",'<span class="elemendas-search-terms elemendas-search-result">'.$search_query.'</span>',$render_text);
		$render_text = str_replace ("{{result-number}}",$post_count,$render_text);
		
		$render_text = sprintf( '<%1$s class="elemendas-results-message">%2$s</%1$s>', \Elementor\Utils::validate_html_tag( $settings['header_size'] ), $render_text );

		echo $render_text;
	}

	protected function content_template() {
		
	}

} //END class Search_Results

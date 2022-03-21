<?php
namespace Elemendas_Addon;

class Search_Results extends \Elementor\Widget_Base {

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

		// Content Tab Start
        $this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'elemendas-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_results',
			[
				'label' => esc_html__( 'Results', 'elemendas-addon' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'Here are the {{result-number}} posts containing {{search-string}}', 'elemendas-addon' ),
				'description' => '<ul><li>'.__('Use {{result-number}} to show the post found number', 'elemendas-addon' ).'</li><li>'.__('Use {{search-string}} to show the search string', 'elemendas-addon' ).'</li></ul>',
			]
		);

		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'elemendas-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'elemendas-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-terms' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab End

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		echo $settings['show_results'];
	}

	protected function content_template() {
		?>
		{{{ settings.title }}}
		<?php
	}

} //END class Search_Results

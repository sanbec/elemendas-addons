<?php
namespace Elemendas_Addons;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class Search_Results extends \Elementor\Widget_Heading {

	public function get_name() {
		return 'search-results';
	}

	public function get_title() {
		return esc_html__( 'Search Results', 'elemendas-addons' );
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

	
	public function get_style_depends() {

		wp_register_style( 'search-results-style', plugins_url( 'assets/css/search-results.css', __FILE__ ) );
		return [
			'search-results-style',
		];

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
				'label' => esc_html__( 'Multiple results', 'elemendas-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 2,
				// translators: Keep {{result-number}} and {{search-string}}  as they are, without translation 
				'default' => __( 'Here are the {{result-number}} posts containing {{search-string}}', 'elemendas-addons' ),
	
					'description' => __('Shown when there are more than one post found', 'elemendas-addons').'<ul><li>'.
					// translators: Keep {{result-number}} as it is, without translation 
					__('Use {{result-number}} to show the post found number', 'elemendas-addons' ).'</li><li>'.
					// translators: Keep {{search-string}}  as it is, without translation 
					__('Use {{search-string}} to show the search string', 'elemendas-addons' ).'</li></ul>',
			]
		);
		
		$this->add_control(
			'show_results_single',
			[
				'label' => esc_html__( 'Only one result', 'elemendas-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 2,
				// translators: Keep {{search-string}}  as it is, without translation 
				'default' => __( 'Here is the unique post containing {{search-string}}', 'elemendas-addons' ),

				'description' =>  __('Shown when a single entry is found', 'elemendas-addons'),
			]
		);
		$this->add_control(
			'show_results_none',
			[
				'label' => esc_html__( 'No results found', 'elemendas-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 2,
				// translators: Keep {{search-string}}  as it is, without translation 
				'default' => __( 'There are no posts containing {{search-string}}', 'elemendas-addons' ),

				'description' =>  __('Shown when there are no posts found', 'elemendas-addons'),
			]
		);


		$this->add_control(
			'html_tag',
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
				'default' => 'h1',
				'separator' => 'before',
			]
		);

		$search_query = get_search_query();
		if (is_null($search_query) || $search_query == '') {
			$this->remove_control('search_query');
			$this->remove_control('post_count');
		} else {
			$this->add_control(
				'search_query',
				[
					'label' => esc_html__( 'Searched string', 'elemendas-addons' ),
					'type' => \Elementor\Controls_Manager::HIDDEN,
					'default' => $search_query,
				]
			);
			$searchall = new \WP_Query("s=$search_query&showposts=-1");
			$post_count = $searchall->post_count;
			$this->add_control(
				'post_count',
				[
					'label' => esc_html__( 'Post count', 'elemendas-addons' ),
					'type' => \Elementor\Controls_Manager::HIDDEN,
					'default' => $post_count,
				]
			);
		}



		$this->end_controls_section();

		// Content Tab End

/*******************
 * Style Tab Start *
 *******************/
		
		// Style section for the whole results message

		$this->start_controls_section(
			'section_results_style',
			[
				'label' => esc_html__( 'Results message', 'elemendas-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .elemendas-results-message' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .elemendas-results-message',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'text_stroke',
				'selector' => '{{WRAPPER}} .elemendas-results-message',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .elemendas-results-message',
			]
		);

		$this->add_control(
			'blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Normal', 'elementor' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'difference' => 'Difference',
					'exclusion' => 'Exclusion',
					'hue' => 'Hue',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .elemendas-results-message' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'none',
			]
		);

		// 
		$this->add_control(
			'size',
			[
				'label' => esc_html__( 'Size', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'description' =>  __('This setting will have no effect if the font size is set in the typography control', 'elemendas-addons'),
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
		
		// Alignment
		
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
		
		// Style section for the searched string
		
		$this->start_controls_section(
			'section_search_string_style',
			[
				'label' => esc_html__( 'Search string highlighting', 'elemendas-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'search_string_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],

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
			return;
		}
		if ( '' === $settings['show_results_none'] ) {
			return;
		}

		if (isset ($settings['search_query'])) {
			$search_query =  $settings['search_query'];
		} else return;

		$this->add_render_attribute( 'show_results', 'class', 'elemendas-results-message' );
		
		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'show_results', 'class', 'elementor-size-' . $settings['size'] );
		}

		$post_count = $settings['post_count'];
		switch ($post_count) {
			case 0:
				$settings['show_results'] = $settings['show_results_none'];
				break;
			case 1:
				$settings['show_results'] = $settings['show_results_single'];
				break;
			default:
				$settings['show_results'] = $settings['show_results_plural'];
				break;
		}

				
		$render_text = $settings['show_results'];
		$render_text = str_replace ("{{search-string}}",'<span class="elemendas-search-terms elemendas-search-result">'.$search_query.'</span>',$render_text);
		$render_text = str_replace ("{{result-number}}",$post_count,$render_text);
		
		$render_text = sprintf( '<%1$s %2$s>%3$s</%1$s>', \Elementor\Utils::validate_html_tag( $settings['html_tag'] ), $this->get_render_attribute_string( 'show_results' ), $render_text );

		echo $render_text;
	}

	protected function content_template() {
		?>
		<#
		if ('' === settings.show_results_plural || '' === settings.show_results_single || '' === settings.show_results_none) {
		#>
			<div class="elemendas-warning">
				<i aria-hidden="true" class="fas fa-exclamation-circle"></i>
				<h4><?=esc_html__('This widget needs to have some text to show', 'elemendas-addons')?></h4>
				<?=esc_html__('In the content area, fill in the fields for:', 'elemendas-addons')?>
				<ul>
					<li>
						<?=esc_html__( 'Multiple results', 'elemendas-addons' )?>.
					</li>
					<li>
						<?=esc_html__( 'Only one result', 'elemendas-addons' )?>.
					</li>
					<li>
						<?=esc_html__( 'No results found', 'elemendas-addons' )?>.
					</li>
				</ul>
			</div>
		<#
		} else {
			if (settings.search_query) {
				view.addRenderAttribute('show_results',	{'class': 'elemendas-results-message',}	);
				if ( settings.size ) {
					view.addRenderAttribute('show_results',	{'class': 'elementor-size-'+settings.size,}	);
				}
				switch (settings.post_count) {
					case 0:
						show_results = settings.show_results_none;
						break;
					case 1:
						show_results = settings.show_results_single;
						break;
					default:
						show_results = settings.show_results_plural;
						break;
				}
				show_results = show_results.replace ('{{search-string}}','<span class="elemendas-search-terms elemendas-search-result">'+settings.search_query+'</span>');
				show_results = show_results.replace ('{{result-number}}',settings.post_count);
			#>
				<{{{ settings.html_tag }}} {{{ view.getRenderAttributeString( 'show_results' ) }}}>{{{ show_results }}}</{{{ settings.html_tag }}}>
			<#
			} else {
			#>
				<div class="elemendas-warning">
					<i aria-hidden="true" class="fas fa-exclamation-circle"></i>
					<h4><?=esc_html__('This widget only works on the search results page', 'elemendas-addons')?></h4>
					<ol>
						<li><?php
							//translators: %s : Preview Settings
							printf( esc_html__('Go to "%s"', 'elemendas-addons'), esc_html__( 'Preview Settings', 'elementor-pro' ))?>
							<i class="eicon-cog" aria-hidden="true"></i>.
						</li>
						<li><?php
							//translators: 1: 'Preview Dynamic Content as', 2: 'Search Results' 3: 'Search Term'
							printf( esc_html__('Set "%1$s" "%2$s" and fill the "%3$s"', 'elemendas-addons'),
										esc_html__( 'Preview Dynamic Content as', 'elementor-pro' ),
										esc_html__( 'Search Results', 'elementor-pro' ),
										esc_html__( 'Search Term', 'elementor-pro' ) )?>.
						</li>
						<li><?php
							//translators: 1: 'Display Conditions' 2: flow icon 3: 'Search Results'
							printf( esc_html__('Adjust the "%1$s" %2$s to "%3$s"', 'elemendas-addons'),
									esc_html__( 'Display Conditions', 'elementor-pro' ),
									'<i class="eicon-flow" aria-hidden="true"></i>',
									esc_html__( 'Search Results', 'elementor-pro' )) ?>.
						</li>
					</ol>
				</div>
			<#
			}
		}
		#>
		<?php
		return;
	}

} //END class Search_Results

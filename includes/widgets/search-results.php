<?php
namespace Elemendas_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class Search_Results extends \Elementor\Widget_Heading {

	public function get_name() {
		return 'search-results';
	}

	public function get_title() {
		return esc_html_x( 'Search Results', 'Widget Name', 'elemendas-addons' );
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

	// BEGIN register_controls
	protected function register_controls() {

/*********************
 * BEGIN Content Tab *
 *********************/

		$this->start_controls_section(
			'content_section_title',
			[
				//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
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

		// The following hidden controls are used to store the search string and the number of found results
		$search_query = get_search_query();
		if (is_null($search_query) || $search_query == '') {
			$this->remove_control('search_query');
			$this->remove_control('post_count');
		} else {
			$this->add_control(
				'search_query',
				[
					'label' => esc_html__( 'Searched string', 'elemendas-addons' ),
					'type' => Controls_Manager::HIDDEN,
					'default' => $search_query,
				]
			);
			$searchall = new \WP_Query("s=$search_query&showposts=-1");
			$post_count = $searchall->post_count;
			$this->add_control(
				'post_count',
				[
					'label' => esc_html__( 'Post count', 'elemendas-addons' ),
					'type' => Controls_Manager::HIDDEN,
					'default' => $post_count,
				]
			);
		}

		$this->end_controls_section();
		// END Content Tab. Note that this section will be followed by the content title section
		// inherited from the heading widget. (See the end of this function)

/*******************
 * BEGIN Style Tab *
 *******************/

		// BEGIN Style section for the searched string

		$this->start_controls_section(
			'section_search_string_style',
			[
				'label' => esc_html__( 'Search string highlighting', 'elemendas-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// BEGIN Highlight Elements Controls

		$this->add_control(
			'highlight-elements',
			[
				'label' => esc_html__( 'Highlight elements', 'elemendas-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		//  Highlighter popover BEGIN

		$this->add_control(
			'highlighter-toggle',
			[
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Highlighter', 'elemendas-addons' ),
			]
		);

		$this->start_popover();

		$this->add_control(
			'search_string_highlighter',
			[
				'label' => esc_html__( 'Highlighter', 'elemendas-addons' ),
				'type' => 'highlighter',
				'default' => ['color' => '#0f07', 'thickness' => '12'],
				'selectors' => [
					'{{WRAPPER}} .elemendas-search-terms' => 'box-shadow: inset 0px -{{THICKNESS}}px {{COLOR}};',
				],
				'condition' => [
					'highlighter-toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$this->end_popover();
		// Highlighter popover END

		// Underline popover BEGIN

		$this->add_control(
			'underline-toggle',
			[
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Underline', 'elemendas-addons' ),
				'selectors' => [
					'{{WRAPPER}} .elemendas-search-terms' => 'text-decoration-skip-ink: none;text-decoration-line: underline;',
				]
			]
		);

		$this->start_popover();

		$this->add_control(
			'search_string_underline_color',
			[
				'label' => esc_html__( 'Color', 'elemendas-addons' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .elemendas-search-terms' => 'text-decoration: underline {{VALUE}};',
				],
				'condition' => [
					'underline-toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);
/*
		$this->add_control(
			'search_string_underline_defaults',
			[
				'type' => Controls_Manager::HIDDEN,
				'selectors' => [
					'{{WRAPPER}} .elemendas-search-terms' => 'text-decoration: underline;',
				]
			]
		);


*/
		$this->add_control(
			'search_string_underline_thickness',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Thickness (px)', 'elemendas-addons' ),
   				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .elemendas-search-terms' => 'text-decoration-thickness: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'underline-toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$this->add_control(
			'search_string_underline_style',
			[
				'type' => Controls_Manager::SELECT2,
				'label' => esc_html__( 'Style', 'elemendas-addons' ),
				'options' => [
					'solid' => esc_html__( 'Solid', 'elemendas-addons' ),
					'double' => esc_html__( 'Double', 'elemendas-addons' ),
					'dotted' => esc_html__( 'Dotted', 'elemendas-addons' ),
					'dashed' => esc_html__( 'Dashed', 'elemendas-addons' ),
					'wavy' => esc_html__( 'Wavy', 'elemendas-addons' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .elemendas-search-terms' => 'text-decoration-style: {{VALUE}};',
				],
				'condition' => [
					'underline-toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$this->add_control(
			'search_string_underline_skip_ink',
			[
				'label' => esc_html__( 'Skip Ink', 'elemendas-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elemendas-addons' ),
				'label_off' => esc_html__( 'No', 'elemendas-addons' ),
				'return_value' => 'all',
				'default' => 'all',
				'description' =>  __('Specifies how underline is drawn when it pass over glyph descenders.', 'elemendas-addons'),
				'selectors' => [
					'{{WRAPPER}} .elemendas-search-terms' => 'text-decoration-skip-ink: {{VALUE}};',
				],
				'condition' => [
					'underline-toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$this->end_popover();
		// Underline popover END

		// Quotation marks
		$this->add_control(
			'quotation_marks',
			[
				'label' => esc_html__( 'Quotation Marks', 'elemendas-addons' ),
				'type' => 'quotation-marks',
				'selectors' => [
					'{{WRAPPER}} .elemendas-search-terms:before' => 'content:"{{OPENQUOTE}}";',
					'{{WRAPPER}} .elemendas-search-terms:after' => 'content:"{{CLOSEQUOTE}}";',
				],
			]
		);
		// END Highlight Elements Controls


		// BEGIN Text Style Search Results Controls

		$this->add_control(
			'text-style',
			[
				'label' => esc_html__( 'Text style', 'elemendas-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Text Color

		$this->add_control(
			'search_string_color',
			[
				//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
				'label' => esc_html__( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elemendas-search-terms' => 'color: {{VALUE}};',
				],
			]
		);

		// Background Color

		$this->add_control(
			'search_string_bgcolor',
			[
				//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
				'label' => esc_html__( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elemendas-search-terms' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'search_string_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .elemendas-search-terms',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'search_string_text_stroke',
				'selector' => '{{WRAPPER}} .elemendas-search-terms',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'search_string_text_shadow',
				'selector' => '{{WRAPPER}} .elemendas-search-terms',
			]
		);
		// END Text Style Search Results Controls

		$this->end_controls_section();
		// END Style Tab. Note that this section will be followed by the style title section inherited from the heading widget.

		parent::register_controls();

		$this->remove_control ('title');
		$this->remove_control ('link');
		$this->update_control(
			'size',
			[
				'description' =>  __('This setting will have no effect if the font size is set in the typography control', 'elemendas-addons'),
			]
		);
	// END Style section
	} // END protected function register_controls

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

		$this->add_render_attribute( 'show_results', 'class', 'elementor-heading-title' );
		
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

		$render_text = sanitize_text_field( $settings['show_results'] );
		$render_text = str_replace ("{{search-string}}",'<span class="elemendas-search-terms elemendas-search-result">'.$search_query.'</span>',$render_text);
		$render_text = str_replace ("{{result-number}}",$post_count,$render_text);
		$render_text = sprintf( '<%1$s %2$s>%3$s</%1$s>',
								\Elementor\Utils::validate_html_tag( $settings['header_size'] ),
								$this->get_render_attribute_string( 'show_results' ),
								$render_text );

		// PHPCS - the variable $render_text holds safe data. Can't be escaped with esc_html as it must deliver html code.
		echo $render_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}  // protected function render

	protected function content_template() {
		?>
		<#
		if ('' === settings.show_results_plural || '' === settings.show_results_single || '' === settings.show_results_none) {
		#>
			<div class="elemendas-warning">
				<i aria-hidden="true" class="fas fa-exclamation-circle"></i>
				<h4><?php echo esc_html__('This widget needs to have some text to show', 'elemendas-addons')?></h4>
				<?php echo esc_html__('In the content area, fill in the fields for:', 'elemendas-addons')?>
				<ul>
					<li>
						<?php echo esc_html__( 'Multiple results', 'elemendas-addons' )?>.
					</li>
					<li>
						<?php echo esc_html__( 'Only one result', 'elemendas-addons' )?>.
					</li>
					<li>
						<?php echo esc_html__( 'No results found', 'elemendas-addons' )?>.
					</li>
				</ul>
			</div>
		<#
		} else {
			if (settings.search_query) {
				view.addRenderAttribute('show_results',	{'class': 'elementor-heading-title',}	);
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
				// something like show_results.sanitize_text_field would be good
				show_results = show_results.replace ('{{search-string}}','<span class="elemendas-search-terms elemendas-search-result">'+settings.search_query+'</span>');
				show_results = show_results.replace ('{{result-number}}',settings.post_count);
			#>
				<{{{ settings.header_size }}} {{{ view.getRenderAttributeString( 'show_results' ) }}}>{{{ show_results }}}</{{{ settings.header_size }}}>
			<#
			} else {
			#>
				<div class="elemendas-warning">
					<i aria-hidden="true" class="fas fa-exclamation-circle"></i>
					<h4><?php echo esc_html__('This widget only works on the search results page', 'elemendas-addons')?></h4>
					<ol>
						<li><?php
							//translators: %s : Preview Settings
							printf( esc_html__('Go to "%s"', 'elemendas-addons'),
									//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
									esc_html__( 'Preview Settings', 'elementor-pro' ))?>
							<i class="eicon-cog" aria-hidden="true"></i>.
						</li>
						<li><?php
							//translators: 1: 'Preview Dynamic Content as', 2: 'Search Results' 3: 'Search Term'
							printf( esc_html__('Set "%1$s" "%2$s" and fill the "%3$s"', 'elemendas-addons'),
										//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
										esc_html__( 'Preview Dynamic Content as', 'elementor-pro' ),
										//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
										esc_html__( 'Search Results', 'elementor-pro' ),
										//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
										esc_html__( 'Search Term', 'elementor-pro' ) )?>.
						</li>
						<li><?php
							//translators: 1: 'Display Conditions' 2: flow icon 3: 'Search Results'
							printf( esc_html__('Adjust the "%1$s" %2$s to "%3$s"', 'elemendas-addons'),
									//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
									esc_html__( 'Display Conditions', 'elementor-pro' ),
									'<i class="eicon-flow" aria-hidden="true"></i>',
									//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
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
	}  //END protected function content_template

} //END class Search_Results

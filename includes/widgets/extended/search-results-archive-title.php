<?php
namespace Elemendas\Addons\Extensions;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Element_Base;

class Search_Results_Archive_Title {
	
	public static function init() {
		// adding and updating widget controls
		add_action( 'elementor/element/theme-archive-title/section_title/after_section_end',  [ __CLASS__, 'add_content_section_title' ] );
		add_action( 'elementor/element/theme-archive-title/section_title_style/before_section_start',  [ __CLASS__, 'update_size_control' ] );
		add_action( 'elementor/element/theme-archive-title/section_title_style/after_section_end',  [ __CLASS__, 'add_section_search_string_style' ] );

		// updating render funtions
		add_filter( 'elementor/widget/render_content',  [ __CLASS__, 'render_search_results'], 10, 2 );
		add_filter( 'elementor/widget/print_template', [ __CLASS__, 'editor_preview_content_template'], 10, 2 );

		//        add_action( 'elementor/element/after_add_attributes',  [ __CLASS__, 'add_attributes' ] );

        /* should enqueue? */
//        add_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        /* add script */
//        add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

    }


	// BEGIN register_controls
	public static function add_content_section_title(Element_Base $element) {

/*********************
 * BEGIN Content Tab *
 *********************/

		$element->start_controls_section(
			'content_section_title',
			[
				//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
				'label' => esc_html__( 'Content', 'elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		// The following hidden controls are used to store the search string and the number of found results
		$search_query = get_search_query();
		if (is_null($search_query) || $search_query == '') {
			$element->remove_control('search_query');
			$element->remove_control('post_count');
			$element->add_control(
				'is_search',
				[
					'label' => esc_html__( 'Searched string', 'elemendas-addons' ),
					'type' => Controls_Manager::HIDDEN,
					'default' => 'no',
				]
			);					
			$element->add_control(
				'only_for_search_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf (
								/* translators: 1: strong open tag, 2: strong closing tag. 3: Widget name */
								esc_html__( '%1$sNote:%2$s This section is only available on the search results page.', 'elemendas-addons' ),
								'<strong>',	'</strong>') . '<ol><li>' .
							 //translators: %s: Preview Settings
							 sprintf( esc_html__('Go to "%s"', 'elemendas-addons'),
									//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
									esc_html__( 'Preview Settings', 'elementor-pro' )) .
							 '<i class="eicon-cog" aria-hidden="true"></i>.</li><li>'.
							 //translators: 1: 'Preview Dynamic Content as', 2: 'Search Results' 3: 'Search Term'
							 sprintf( esc_html__('Set "%1$s" "%2$s" and fill the "%3$s"', 'elemendas-addons'),
										//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
										esc_html__( 'Preview Dynamic Content as', 'elementor-pro' ),
										//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
										esc_html__( 'Search Results', 'elementor-pro' ),
										//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
										esc_html__( 'Search Term', 'elementor-pro' ) ) .
							 '.</li><li>' .
							//translators: 1: 'Display Conditions' 2: flow icon 3: 'Search Results'
							 sprintf( esc_html__('Adjust the "%1$s" %2$s to "%3$s"', 'elemendas-addons'),
									//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
									esc_html__( 'Display Conditions', 'elementor-pro' ),
									'<i class="eicon-flow" aria-hidden="true"></i>',
									//translators: Don't worry about this string, it will actually take it from Elementor's translation file for consistency.
									esc_html__( 'Search Results', 'elementor-pro' )) .
							'.</li></ol></div>',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning elemendas-warning',
					'condition' => [
						'is_search' => 'no', 
					],				
				]
			);

		} else {
			$element->add_control(
				'is_search',
				[
					'label' => esc_html__( 'Searched string', 'elemendas-addons' ),
					'type' => Controls_Manager::HIDDEN,
					'default' => 'yes',
				]
			);			
			$element->add_control(
				'search_query',
				[
					'label' => esc_html__( 'Searched string', 'elemendas-addons' ),
					'type' => Controls_Manager::HIDDEN,
					'default' => $search_query,
				]
			);
			$searchall = new \WP_Query("s=$search_query&showposts=-1");
			$post_count = $searchall->post_count;
			$element->add_control(
				'post_count',
				[
					'label' => esc_html__( 'Post count', 'elemendas-addons' ),
					'type' => Controls_Manager::HIDDEN,
					'default' => $post_count,
				]
			);			$element->add_control(
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
			
			$element->add_control(
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

			$element->add_control(
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


		}

		$element->end_controls_section();
		// END Content Tab. 
	}



	public static function add_section_search_string_style (Element_Base $element) {

/*******************
 * BEGIN Style Tab *
 *******************/

		// BEGIN Style section for the searched string

		$element->start_controls_section(
			'section_search_string_style',
			[
				'label' => esc_html__( 'Search string highlighting', 'elemendas-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// BEGIN Highlight Elements Controls

		$element->add_control(
			'highlight_elements',
			[
				'label' => esc_html__( 'Highlight elements', 'elemendas-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		//  Highlighter popover BEGIN

		$element->add_control(
			'highlighter_toggle',
			[
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Highlighter', 'elemendas-addons' ),
			]
		);

		$element->start_popover();

		$element->add_control(
			'search_string_highlighter',
			[
				'label' => esc_html__( 'Highlighter', 'elemendas-addons' ),
				'type' => 'highlighter',
				'default' => ['color' => '#0f07', 'thickness' => '12'],
				'selectors' => [
					'{{WRAPPER}} .elemendas-search-terms' => 'box-shadow: inset 0px -{{THICKNESS}}px {{COLOR}};',
				],
				'condition' => [
					'highlighter_toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$element->end_popover();
		// Highlighter popover END

		// Underline popover BEGIN

		$element->add_control(
			'underline_toggle',
			[
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Underline', 'elemendas-addons' ),
				'selectors' => [
					'{{WRAPPER}} .elemendas-search-terms' => 'text-decoration-skip-ink: none;text-decoration-line: underline;',
				]
			]
		);

		$element->start_popover();

		$element->add_control(
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
					'underline_toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$element->add_control(
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
					'underline_toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$element->add_control(
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
					'underline_toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$element->add_control(
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
					'underline_toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$element->end_popover();
		// Underline popover END

		// Quotation marks
		$element->add_control(
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

		$element->add_control(
			'text_style',
			[
				'label' => esc_html__( 'Text style', 'elemendas-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Text Color

		$element->add_control(
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

		$element->add_control(
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

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'search_string_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .elemendas-search-terms',
			]
		);

		$element->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'search_string_text_stroke',
				'selector' => '{{WRAPPER}} .elemendas-search-terms',
			]
		);

		$element->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'search_string_text_shadow',
				'selector' => '{{WRAPPER}} .elemendas-search-terms',
			]
		);
		// END Text Style Search Results Controls

		$element->end_controls_section();
		// END Style Tab. Note that this section will be followed by the style title section inherited from the heading widget.
	}
	public static function update_size_control (Element_Base $element) {

		$element->update_control(
			'size',
			[
				'description' =>  __('This setting will have no effect if the font size is set in the typography control', 'elemendas-addons'),
			]
		);
	// END Style section
	} // END protected function register_controls

	
	
	public static function render_search_results ( $widget_content, $element ) {

		if ( 'theme-archive-title' === $element->get_name() ) {
			$settings = $element->get_settings_for_display();
			if (isset ($settings['search_query'])) {
				$search_query =  $settings['search_query'];
			} else return $widget_content;
			if ( '' === $settings['show_results_plural'] ) {
				return $widget_content;
			}
			if ( '' === $settings['show_results_single'] ) {
				return $widget_content;
			}
			if ( '' === $settings['show_results_none'] ) {
				return $widget_content;
			}
			$element->add_render_attribute( 'show_results', 'class', 'elementor-heading-title' );

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
			$title = $settings['title'];
			$show_results = sanitize_text_field( $settings['show_results'] );
			$widget_content = str_replace ($title, $show_results, $widget_content);
			
			
			$widget_content = str_replace ("{{search-string}}",'<span class="elemendas-search-terms elemendas-search-result">'.$search_query.'</span>',$widget_content);
			$widget_content = str_replace ("{{result-number}}",$post_count,$widget_content);
			$widget_content = sprintf( '<%1$s %2$s>%3$s</%1$s>',
									\Elementor\Utils::validate_html_tag( $settings['header_size'] ),
									$element->get_render_attribute_string( 'show_results' ),
									$widget_content );

		}
		return $widget_content;
	}

	public static function editor_preview_content_template($template, $element) {
		if ( 'theme-archive-title' === $element->get_name() ) {

			$template = <<<"term"
					<#
						if ('' === settings.show_results_plural || '' === settings.show_results_single || '' === settings.show_results_none || !settings.search_query) {
							var title = settings.title;
						} else {
							switch (settings.post_count) {
								case 0:
									title = settings.show_results_none;
									break;
								case 1:
									title = settings.show_results_single;
									break;
								default:
									title = settings.show_results_plural;
									break;
							}
							// something like title.sanitize_text_field would be good
							title = title.replace ('{{search-string}}','<span class="elemendas-search-terms elemendas-search-result">'+settings.search_query+'</span>');
							title = title.replace ('{{result-number}}',settings.post_count);
						}

						if ( '' !== settings.link.url ) {
                            title = '<a href="' + settings.link.url + '">' + title + '</a>';
                        }

                        view.addRenderAttribute( 'title', 'class', [ 'elementor-heading-title', 'elementor-size-' + settings.size ] );

                        view.addInlineEditingAttributes( 'title' );

                        var headerSizeTag = elementor.helpers.validateHTMLTag( settings.header_size ),
                            title_html = '<' + headerSizeTag  + ' ' + view.getRenderAttributeString( 'title' ) + '>' + title + '</' + headerSizeTag + '>';

                        print( title_html );
					#>
term;
		}
		return $template;
	}  //END protected function content_template

} //END class Search_Results

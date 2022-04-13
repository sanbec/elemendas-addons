<?php
namespace Elemendas_Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class Search_Results_Highlighted extends \Elementor\Widget_Heading {

	public function get_name() {
		return 'search-results-highlighted';
	}

	public function get_title() {
		return esc_html_x( 'Search Results Highlighted', 'Widget Name', 'elemendas-addons' );
	}

	public function get_icon() {
		return 'fas fa-highlighter';
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
		wp_register_style( 'search-results-highlighted-style', plugins_url( 'assets/css/search-results-highlighted.css', __FILE__ ), false, ELEMENDAS_ADDONS_VERSION );
		return [
			'search-results-highlighted-style',
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
			'section',
			[
				'label' => esc_html_x( 'Search Results Highlighted', 'Widget Name', 'elemendas-addons' ),
			]
		);

		$this->add_control(
			'where_to_appear_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'Drop this widget anywhere on the search results archive template.', 'elemendas-addons' ),
				'content_classes' => 'elementor-control-wp',
			]
		);

		$this->add_control(
			'one_per_page_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => sprintf(
					/* translators: 1: strong open tag, 2: strong closing tag. 3: Widget name */
					esc_html__( '%1$sNote:%2$s You can only add the %3$s widget once.', 'elemendas-addons' ),
					'<strong>',	'</strong>', esc_html_x( 'Search Results Highlighted', 'Widget Name', 'elemendas-addons' )
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
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
		$this->start_controls_section(
			'section_search_string_style',
			[
				'label' => esc_html__( 'Search string highlighting', 'elemendas-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// BEGIN Highlight Elements Controls

		$this->add_control(
			'search_string_highlight_separately',
			[
				'label' => esc_html__( 'Highlight each word separately', 'elemendas-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elemendas-addons' ),
				'label_off' => esc_html__( 'No', 'elemendas-addons' ),
				'return_value' => 'yes',
				'default' => 'no',
				'description' =>  __('Choose between highlighting the whole string or each word separately.', 'elemendas-addons'),
			]
		);

		$this->add_control(
			'highlight_elements',
			[
				'label' => esc_html__( 'Highlight elements', 'elemendas-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->start_controls_tabs(
			'style_tabs'
		);

		// BEGIN Titles tab
		$this->start_controls_tab(
			'style_title_tab',
			[
				'label' => esc_html__( 'Titles', 'elemendas-addons' ),
			]
		);

		//  Titles tab - Highlighter popover BEGIN

		$this->add_control(
			'titles_highlighter_toggle',
			[
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Highlighter', 'elemendas-addons' ),
			]
		);

		$this->start_popover();

		$this->add_control(
			'titles_highlighter',
			[
				'label' => esc_html__( 'Highlighter', 'elemendas-addons' ),
				'type' => 'highlighter',
				'default' => ['color' => '#0f07', 'thickness' => '12'],
				'selectors' => [
					'span.elemendas-highlight' => 'box-shadow: inset 0px -{{THICKNESS}}px {{COLOR}};',
				],
				'condition' => [
					'titles_highlighter_toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$this->end_popover();
		// Titles tab - Highlighter popover END

		// Titles tab - Underline popover BEGIN

		$this->add_control(
			'titles_underline_toggle',
			[
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Underline', 'elemendas-addons' ),
				'selectors' => [
					'span.elemendas-highlight' => 'text-decoration-skip-ink: none;text-decoration-line: underline;',
				]
			]
		);

		$this->start_popover();

		$this->add_control(
			'titles_underline_color',
			[
				'label' => esc_html__( 'Color', 'elemendas-addons' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'span.elemendas-highlight' => 'text-decoration-color: {{VALUE}};',
				],
				'condition' => [
					'titles_underline_toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$this->add_control(
			'titles_underline_thickness',
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
					'span.elemendas-highlight' => 'text-decoration-thickness: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'titles_underline_toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$this->add_control(
			'titles_underline_style',
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
					'span.elemendas-highlight' => 'text-decoration-style: {{VALUE}};',
				],
				'condition' => [
					'titles_underline_toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$this->add_control(
			'titles_underline_skip_ink',
			[
				'label' => esc_html__( 'Skip Ink', 'elemendas-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elemendas-addons' ),
				'label_off' => esc_html__( 'No', 'elemendas-addons' ),
				'return_value' => 'all',
				'default' => 'all',
				'description' =>  __('Specifies how underline is drawn when it pass over glyph descenders.', 'elemendas-addons'),
				'selectors' => [
					'.elemendas-highlight' => 'text-decoration-skip-ink: {{VALUE}};',
				],
				'condition' => [
					'titles_underline_toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$this->end_popover();
		// Titles tab - Underline popover END
		$this->end_controls_tab();
		// END Titles Tab

		// BEGIN Excerpts tab
		$this->start_controls_tab(
			'style_excerpts_tab',
			[
				'label' => esc_html__( 'Excerpts', 'elemendas-addons' ),
			]
		);
		//  Excerpts tab - Highlighter popover BEGIN

		$this->add_control(
			'excerpts_highlighter_toggle',
			[
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Highlighter', 'elemendas-addons' ),
			]
		);

		$this->start_popover();

		$this->add_control(
			'excerpts_highlighter',
			[
				'label' => esc_html__( 'Highlighter', 'elemendas-addons' ),
				'type' => 'highlighter',
				'default' => ['color' => '#0f07', 'thickness' => '12'],
				'selectors' => [
					'span.elemendas-highlight.elemendas-search-excerpt' => 'box-shadow: inset 0px -{{THICKNESS}}px {{COLOR}};',
				],
				'condition' => [
					'excerpts_highlighter_toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$this->end_popover();
		// Excerpts tab - Highlighter popover END

		// Excerpts tab - Underline popover BEGIN

		$this->add_control(
			'excerpts_underline_toggle',
			[
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Underline', 'elemendas-addons' ),
				'selectors' => [
					'span.elemendas-highlight.elemendas-search-excerpt' => 'text-decoration-skip-ink: none;text-decoration-line: underline;',
				]
			]
		);

		$this->start_popover();

		$this->add_control(
			'excerpts_underline_color',
			[
				'label' => esc_html__( 'Color', 'elemendas-addons' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'span.elemendas-highlight.elemendas-search-excerpt' => 'text-decoration-color: {{VALUE}};',
				],
				'condition' => [
					'excerpts_underline_toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$this->add_control(
			'excerpts_underline_thickness',
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
					'span.elemendas-highlight.elemendas-search-excerpt' => 'text-decoration-thickness: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'excerpts_underline_toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$this->add_control(
			'excerpts_underline_style',
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
					'span.elemendas-highlight.elemendas-search-excerpt' => 'text-decoration-style: {{VALUE}};',
				],
				'condition' => [
					'excerpts_underline_toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$this->add_control(
			'excerpts_underline_skip_ink',
			[
				'label' => esc_html__( 'Skip Ink', 'elemendas-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elemendas-addons' ),
				'label_off' => esc_html__( 'No', 'elemendas-addons' ),
				'return_value' => 'all',
				'default' => 'all',
				'description' =>  __('Specifies how underline is drawn when it pass over glyph descenders.', 'elemendas-addons'),
				'selectors' => [
					'span.elemendas-highlight.elemendas-search-excerpt' => 'text-decoration-skip-ink: {{VALUE}};',
				],
				'condition' => [
					'excerpts_underline_toggle' => 'yes', // by adding condition to popover switch, we are limiting this settings effect only when the popover is active.
				],
			]
		);

		$this->end_popover();
		// Excerpts tab - Underline popover END
		$this->end_controls_tab();
		// END Excerpts tab
		$this->end_controls_tabs();

		// END Highlight Elements Controls


		// BEGIN Text Style Search Results Controls

		$this->add_control(
			'text_style',
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
					'span.elemendas-highlight' => 'color: {{VALUE}};',
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
					'span.elemendas-highlight' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'search_string_typography',
				'selector' => 'span.elemendas-highlight',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'search_string_text_stroke',
				'selector' => 'span.elemendas-highlight',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'search_string_text_shadow',
				'selector' => 'span.elemendas-highlight',
			]
		);
		// END Text Style Search Results Controls

		$this->end_controls_section();
	// END Style section
	} // END protected function register_controls

	public function search_results_highlight($text, $id = null) {
		$settings = $this->get_settings_for_display();

		if( is_search()) {
			$search_query =  $settings['search_query'];

			if (is_null($id)) {
				$class = 'elemendas-search-excerpt';
			} else {
				$class = 'elemendas-search-title';
			}
			if ( !is_nav_menu_item($id) ) // we do not want to highlight by mistake a title that is in the menu.
				if ('yes' === $settings['search_string_highlight_separately']) {
					$keys= explode(" ",$search_query);
					$text = preg_replace('/('.implode('|', $keys) .')/iu', '<span class="elemendas-highlight '.$class.'">\0</span>', $text);
				} else {
					$text = preg_replace('/('.$search_query.')/iu', '<span class="elemendas-highlight '.$class.'">\0</span>', $text);
				}
		}
		return $text;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		if (isset ($settings['search_query'])) {
			$search_query =  $settings['search_query'];
		} else return;

		// Warning! This can affect menu entries and anything else that displays entries (e.g. sidebars).
		// This must be considered when creating the css rules for the .elemendas-search-excerpt & .elemendas-search-title classes
		// at the results posts title
		add_filter( 'the_title',   [ $this, 'search_results_highlight' ] ,10,2 );
		// at the results posts excerpt
		add_filter( 'the_excerpt', [ $this, 'search_results_highlight' ] );
	}  // protected function render

	protected function content_template() {
		?>
		<#
			if (settings.search_query) {
			#>
				<div class="elementor-panel-alert elementor-panel-alert-info elemendas-notice">
					<i aria-hidden="true" class="fas fa-highlighter"></i>
					<span><?php
						// translators: %s: widget name
						printf( esc_html__('%s widget. You won\'t see this while previewing your site.', 'elemendas-addons'),
								esc_html_x( 'Search Results Highlighted', 'Widget Name', 'elemendas-addons' ));?>
					</span>
				</div>
			<#
			} else {
			#>
				<div class="elementor-panel-alert elementor-panel-alert-warning elemendas-warning elemendas-warning">
					<i aria-hidden="true" class="fas fa-exclamation-circle"></i>
					<h4><?php echo esc_html__('This widget only works on the search results page', 'elemendas-addons')?></h4>
					<ol>
						<li><?php
							//translators: %s: Preview Settings
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
		#>
		<?php
		return;
	}  //END protected function content_template

} //END class Search_Results

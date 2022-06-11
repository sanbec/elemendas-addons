<?php
namespace Elemendas\Addons\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor choose control.
 *
 * A base control for creating choose control. Displays radio buttons styled as
 * groups of buttons with icons for each option.
 *
 * @since 1.0.0
 */
class Border_Style_Control extends \Elementor\Base_Data_Control {

	/**
	 * Get choose control type.
	 *
	 * Retrieve the control type, in this case `choose`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'border-style';
	}
	/**
	 * Enqueue scripts ans styles.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function enqueue() {
		wp_enqueue_script( 'border-style', plugins_url( 'assets/js/border-style.js', __FILE__ ), false, ELEMENDAS_ADDONS_VERSION );
		wp_enqueue_style( 'border-style', plugins_url( 'assets/css/border-style.css', __FILE__ ), false, ELEMENDAS_ADDONS_VERSION );
	}
	/**
	 * Render choose control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	protected function get_supported_options() {
		return [
					'solid' => [
						'title' => esc_html_x( 'Solid', 'Border Control', 'elementor' ),
						'icon' => 'elm elm-border elm-solid',
					],
					'double' => [
						'title' => esc_html_x( 'Double', 'Border Control', 'elementor' ),
						'icon' => 'elm elm-border elm-double',
					],
					'dotted' => [
						'title' => esc_html_x( 'Dotted', 'Border Control', 'elementor' ),
						'icon' => 'elm elm-border elm-dotted',
					],
					'dashed' => [
						'title' => esc_html_x( 'Dashed', 'Border Control', 'elementor' ),
						'icon' => 'elm elm-border elm-dashed',
					],
					'groove' => [
						'title' => esc_html_x( 'Groove', 'Border Control', 'elementor' ),
						'icon' => 'elm elm-border-outdated elm-groove',
					],
					'ridge' => [
						'title' => esc_html_x( 'Ridge', 'Border Control', 'elemendas-addons' ),
						'icon' => 'elm elm-border-outdated elm-ridge',
					],
					'inset' => [
						'title' => esc_html_x( 'Inset', 'Border Control', 'elemendas-addons' ),
						'icon' => 'elm elm-border-outdated elm-inset',
					],
					'outset' => [
						'title' => esc_html_x( 'Outset', 'Border Control', 'elemendas-addons' ),
						'icon' => 'elm elm-border-outdated elm-outset',
					],
		];
	}

	public function content_template() {
		$control_uid_input_type = '{{value}}';
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-choices">
			<#
			var includedStyles;
			if (_.isEmpty(data.include)) {
				includedStyles = ['solid','double','dotted','dashed']; // default styles displayed
			} else {
				includedStyles = data.include;
			}
			var enabled_options;
			if ('all'==includedStyles) {
				enabled_options = data.options;
			} else {
				enabled_options = _.pick(data.options, includedStyles);
			}
			_.each( enabled_options, function( options, value ) { #>
				<input id="<?php $this->print_control_uid( $control_uid_input_type ); ?>" type="radio" name="elementor-choose-{{ data.name }}-{{ data._cid }}" value="{{ value }}">
				<label class="elementor-choices-label tooltip-target" for="<?php $this->print_control_uid( $control_uid_input_type ); ?>" data-tooltip="{{ options.title }}" title="{{ options.title }}">
					<i class="{{ options.icon }}" aria-hidden="true"></i>
					<span class="elementor-screen-only">{{{ options.title }}}</span>
				</label>
			<# } ); #>
			</div>
		</div>

		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>

		<?php
	}

	/**
	 * Get choose control default settings.
	 *
	 * Retrieve the default settings of the choose control. Used to return the
	 * default settings while initializing the choose control.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
				'options' => $this->get_supported_options(),
				'include' => [],
				'toggle' => true,
		];
	}
}

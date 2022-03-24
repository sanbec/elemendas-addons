<?php
namespace Elemendas_Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elemendas quotation marks control.
 *
 * A control for displaying a select field with the ability to choose quotation marks.
 *
 *
 * @since 1.0.0
 */
class Elemendas_Quotation_Control extends \Elementor\Control_Base_Multiple {

	/**
	 * Get quotation marks control type.
	 *
	 * Retrieve the control type, in this case `quotation-marks`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'quotation-marks';
	}

	/**
	 * Enqueue scripts ans styles.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function enqueue() {
		wp_enqueue_script( 'quotation-marks', plugins_url( 'assets/js/QuotationMarks.js', __FILE__ ) );
		wp_enqueue_style( 'quotation-marks', plugins_url( 'assets/css/QuotationMarks.css', __FILE__ ) );
	}

	/**
	 * Get quotation marks.
	 *
	 * Retrieve all the available quotation marks.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return array Available quotation marks.
	 */

	public static function get_quotation_marks() {
		return [
			'angular double' => ['«','»'],
			'angular single' => ['‹','›'],
			'english double' => ['“','”'],
			'english single' => ['‘','’'],
			'german double' => ['„','“'],
			'german single' => ['‚','‘'],
		];
	}

	/**
	 * Get quotation marks control default settings.
	 *
	 * Retrieve the default settings of the quotation marks control. Used to return
	 * the default settings while initializing the quotation marks control.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return array Currency control default settings.
	 */
	protected function get_default_settings() {
		return [
			'quotation_marks' => self::get_quotation_marks()
		];
	}

	/**
	 * Get quotation marks control default value.
	 *
	 * Retrieve the default value of the quotation marks control. Used to return the
	 * default value while initializing the control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Currency control default value.
	 */
	public function get_default_value() {
		return [
			'quotesType' => '',
			'openQuote' => '',
			'closeQuote' => '',
		];
	}

	/**
	 * Render quotation marks control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		?>
		<div class="elementor-control-field">
			<# if ( data.label ) {#>
			<label for="<?php $this->print_control_uid( 'quotesType' ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>

			<div class="elementor-control-input-wrapper elemendas-quotation-marks">
				<select id="<?php $this->print_control_uid( 'quotesType' ); ?>" data-setting="quotesType">
					<option value=""><?php echo esc_html__( 'Select style', 'elemendas-addons' ); ?></option>
					<# _.each( data.quotation_marks, function( quotation_value, quotation_label ) { #>
					<option value="{{{ quotation_value }}}">{{ quotation_value[0] }}{{{ quotation_label }}}{{ quotation_value[1] }}</option>
					<# } ); #>
				</select>
				<input id="<?php $this->print_control_uid( 'openQuote' ); ?>" type="text" data-setting="openQuote" />
				<input id="<?php $this->print_control_uid( 'closeQuote' ); ?>" type="text" data-setting="closeQuote" />
			</div>
		</div>

		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}

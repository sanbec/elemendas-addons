<?php
namespace Elemendas\Addons\Controls;

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
class Quotation_Control extends \Elementor\Control_Base_Multiple {

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
		wp_enqueue_script( 'quotation-marks', plugins_url( 'assets/js/QuotationMarks.js', __FILE__ ), false, ELEMENDAS_ADDONS_VERSION );
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
			esc_html_x('angular double', 'Quotation Marks Dropdown Selector', 'elemendas-addons' ) => ['«','»'],
			esc_html_x('angular single', 'Quotation Marks Dropdown Selector', 'elemendas-addons' ) => ['‹','›'],
			esc_html_x('english double', 'Quotation Marks Dropdown Selector', 'elemendas-addons' ) => ['“','”'],
			esc_html_x('english single', 'Quotation Marks Dropdown Selector', 'elemendas-addons' ) => ['‘','’'],
			esc_html_x('german double' , 'Quotation Marks Dropdown Selector', 'elemendas-addons' ) => ['„','“'],
			esc_html_x('german single' , 'Quotation Marks Dropdown Selector', 'elemendas-addons' ) => ['‚','‘'],
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
	 * @return array Quotation Marks Control default settings.
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
	 * @return array Quotation Marks control default value.
	 */
	public function get_default_value() {
		return [
			'quotesType' => '',
			'openquote' => '',
			'closequote' => '',
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
		$quotes = [
			'openquote',
			'closequote',
		];

		?>
		<div class="elementor-control-field">
			<# if ( data.label ) {#>
			<label for="<?php $this->print_control_uid( 'quotesType' ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>

			<div class="elementor-control-input-wrapper elemendas-quotation-marks">
				<select id="<?php $this->print_control_uid( 'quotesType' ); ?>" data-setting="quotesType">
					<option value=""><?php echo esc_html__( 'No quotation marks', 'elemendas-addons' ); ?></option>
					<# _.each( data.quotation_marks, function( quotation_value, quotation_label ) { #>
					<option value="{{{ quotation_value }}}">{{ quotation_value[0] }}{{{ quotation_label }}}{{ quotation_value[1] }}</option>
					<# } ); #>
				</select>

				<?php foreach ( $quotes as $quote) : $control_uid = $this->get_control_uid( $quote ); ?>
				<input id="<?php echo $control_uid; ?>" type="hidden" data-name="{{data.name}}-<?php echo esc_attr( $quote ); ?>" data-setting="<?php echo esc_attr( $quote ); ?>"/>
				<?php endforeach; ?>

			</div>
		</div>

		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}

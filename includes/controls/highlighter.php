<?php
namespace Elemendas_Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elemendas highlighter control.
 *
 * A control for highlight a text using box-shadow css property.
 *
 *
 * @since 1.0.0
 */
class Highlighter_Control extends \Elementor\Control_Base_Multiple {

	/**
	 * Get highlighter control type.
	 *
	 * Retrieve the control type, in this case `highlighter`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'highlighter';
	}

	/**
	 * Enqueue scripts ans styles.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function enqueue() {
		wp_enqueue_script( 'highlighter', plugins_url( 'assets/js/highlighter.js', __FILE__ ), false, ELEMENDAS_ADDONS_VERSION );
		wp_enqueue_style( 'highlighter', plugins_url( 'assets/css/highlighter.css', __FILE__ ), false, ELEMENDAS_ADDONS_VERSION );
	}

	/**
	 * Get highlighter control default value.
	 *
	 * Retrieve the default value of the highlighter control. Used to return the
	 * default values while initializing the highlighter control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control default value.
	 */
	public function get_default_value() {
		return [
			'thickness' => 12,
			'color' => '#0f07',
		];
	}

	/**
	 * Get highlighter control sliders.
	 *
	 * Retrieve the sliders of the highlighter control. Sliders are used while
	 * rendering the control output in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control sliders.
	 */
	public function get_sliders() {
		return [
			'thickness' => [
				'label' => esc_html__( 'Thickness (px)', 'elemendas-addons' ),
				'min' => 0,
				'max' => 100,
			],
		];
	}

	/**
	 * Render highlighter control output in the editor.
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
		<div class="elemendas-highlighter">
			<div class="elementor-control-field elementor-color-picker-wrapper">
				<label class="elementor-control-title"><?php echo esc_html__( 'Color', 'elementor' ); ?></label>
				<div class="elementor-control-input-wrapper elementor-control-unit-1">
					<div class="elementor-color-picker-placeholder"></div>
				</div>
			</div>
			<?php
			foreach ( $this->get_sliders() as $slider_name => $slider ) :
				?>
				<div class="elementor-shadow-slider elementor-control-type-slider">
					<label for="<?php $this->print_control_uid( $slider_name ); ?>" class="elementor-control-title"><?php
						// PHPCS - the value of $slider['label'] is already escaped.
						echo $slider['label']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?></label>
					<div class="elementor-control-input-wrapper">
						<div class="elementor-slider" data-input="<?php echo esc_attr( $slider_name ); ?>"></div>
						<div class="elementor-slider-input elementor-control-unit-2">
							<input id="<?php $this->print_control_uid( $slider_name ); ?>" type="number" min="<?php echo esc_attr( $slider['min'] ); ?>" max="<?php echo esc_attr( $slider['max'] ); ?>" data-setting="<?php echo esc_attr( $slider_name ); ?>"/>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}

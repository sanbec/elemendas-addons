<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('acf_field_svg_icon_picker') ) :

class acf_field_svg_icon_picker extends acf_field {

	function __construct( $settings ) {

		$this->name = 'icon-picker';

		$this->label = __('Icon Picker', 'elemendas-addons');

		$this->category = 'jquery';

		$this->defaults = array(
			'initial_value'	=> '',
		);

		$this->l10n = array(
			'error'	=> __('Error!', 'elemendas-addons'),
		);

		$this->settings = $settings;




//		$this->folder = apply_filters( 'acf_icon_folder', 'assets/img/svg/' );
		$this->folder = apply_filters( 'acf_icon_folder', 'elemendas-svg-icons/' );

		$this->path = apply_filters( 'acf_icon_path', $this->settings['icons_path'] ) . $this->folder;

		$this->url = apply_filters( 'acf_icon_url', $this->settings['icons_url'] ) . $this->folder;

		$this->plugin_url = $this->settings['plugin_url'];

//		$priority_dir_lookup = get_stylesheet_directory() . '/' . $this->folder;

//		if ( file_exists( $priority_dir_lookup ) ) {
//			$this->path = $priority_dir_lookup;
//			$this->url = get_stylesheet_directory_uri() . '/' . $this->folder;
//		}

		$this->svgs = array();
		$this->iconSets = array();

		$folders = $this->scandirRecursively( $this->path );
//		var_dump($folders);
//		echo "<br>";
		foreach ($folders as $dir => $files) {
			foreach ($files as $file) {
				if( pathinfo($file, PATHINFO_EXTENSION) == 'svg' ){
					$exploded = explode('.', $file);
					$icon = array(
						'name' => $exploded[0],
						'icon' => $dir.'/'.$file
					);
					array_push($this->svgs, $icon);
				}
			}
			$this->iconSets[]=$dir;
		}
//		var_dump($this->svgs);
//		echo "<br>";

    	parent::__construct();
	}


	function scandirRecursively($dir) {
		$result = array();

		$cdir = array_diff(scandir($dir), array('.', '..'));

		foreach ($cdir as $key => $value)
		{
			if (!in_array($value,array(".","..")))
			{
				if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
				{
					$result[$value] = $this->scandirRecursively($dir . DIRECTORY_SEPARATOR . $value);
				}
				else
				{
					$result[] = $value;
				}
			}
		}

		return $result;
	}

	function render_field( $field ) {
		$input_icon = $field['value'] != "" ? $field['value'] : $field['initial_value'];
		$svg = $this->path . $input_icon;
		?>
			<div class="acf-icon-picker">
				<div class="acf-icon-picker__img">
					<?php
						if ( $input_icon !=='' && file_exists( $svg ) ) {
							$svg = $this->url . $input_icon;
							echo '<div class="acf-icon-picker__svg">';
						   	echo '<img src="'.$svg.'" alt=""/>';
						    echo '</div>';
						}else{
							echo '<div class="acf-icon-picker__svg">';
							echo '<span class="acf-icon-picker__svg--span">&plus;</span>';
						    echo '</div>';
						}
					?>
					<input type="hidden" readonly name="<?php echo esc_attr($field['name']) ?>" value="<?php echo esc_attr($input_icon) ?>"/>
				</div>
				<?php if ( $field['required' ] == false ) { ?>
					<span class="acf-icon-picker__remove">
						<div title="remove" aria-label="remove" class="dashicons dashicons-trash" style="color: red;"></div>
					</span>
				<?php } ?>
			</div>
		<?php
	}

	function input_admin_enqueue_scripts() {

		$url = $this->plugin_url;

		wp_register_script( 'acf-input-icon-picker', "{$url}assets/js/input.js", ['acf-input','wp-i18n'], ELEMENDAS_ADDONS_VERSION );
		wp_enqueue_script('acf-input-icon-picker');

		$inline_script =  'const path='.json_encode($this->url).';';
		$inline_script .= 'const plugin_url='.json_encode($url).';';
		$inline_script .= 'const allSVGs='.json_encode($this->svgs).';';
		$inline_script .= 'let iconSets='.json_encode($this->iconSets).';';
		wp_add_inline_script( 'acf-input-icon-picker', $inline_script, 'before');

		wp_localize_script( 'acf-input-icon-picker', 'i10nStr',
							array (
									'no_icons_msg' => 		sprintf( esc_html__('To add icons, add your svg files in the /%s folder in the WordPress uploads folder.', 'elemendas-addons'), $this->folder),
									'upload_icons_msg' => 	sprintf ( __('In order to upload new icons to the "Uploaded Icons" folder, go to %1$s %2$s > %3$s %4$s', 'elemendas-addons') ,
																			'<a href="themes.php?page=elmadd-upload-custom-icons">' ,
																			__( 'Appearance' ),
																			__('Upload Menu Icons', 'elemendas-addons') ,
																			'</a>'
															),
							)
		);

		wp_register_style( 'acf-input-icon-picker', "{$url}assets/css/input.css", array('acf-input'), ELEMENDAS_ADDONS_VERSION );
		wp_enqueue_style('acf-input-icon-picker');
	}
}
new acf_field_svg_icon_picker( $this->settings );

endif;

?>

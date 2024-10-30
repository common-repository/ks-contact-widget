<?php
/*
Plugin Name: KS Contact Widget
Plugin URI: http://wordpress.org/plugins/ks-contact-widget/
Description: A advance contact for widget.
Author: King Soft
Author URI: http://facebook.com/89dungsithanhkhe/

Version: 2.0

Text Domain: ks-contact-widget
Domain Path: /languages

License: GNU General Public License v2.0 (or later)
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

add_action( 'plugins_loaded', 'ks_contact_widget_load_textdomain' );
/**
 * Load textdomain
 */
function ks_contact_widget_load_textdomain() {
	load_plugin_textdomain( 'ks-contact-widget', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}

add_filter('widget_text', 'do_shortcode');

class KS_Contact_Widget_Widget extends WP_Widget {

	const VERSION = '2.0';

	/**
	* Display a thank you nag when the plugin has been upgraded.
	*/
	public function ks_contact_upgrade_nag() {
		if ( !current_user_can('install_plugins') ) return;

		$version_key = '_contact_widget_version';
		if ( get_site_option( $version_key ) == self::VERSION ) return;

		$msg = sprintf(__('Thanks for upgrading the KS Contact Widget! Please go to <a href="%s" target="_blank">here</a> and maybe maybe download more scripts or themes or plugins premium!', 'ads-widget'),'http://aioresources.net');
		echo "<div class='update-nag'>$msg</div>";

		update_site_option( $version_key, self::VERSION );
	}

	/**
	 * Default widget values.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Default widget values.
	 *
	 * @var array
	 */
	protected $sizes;

	/**
	 * Default widget profile glyphs.
	 *
	 * @var array
	 */
	protected $glyphs;

	/**
	 * Default widget profile values.
	 *
	 * @var array
	 */
	protected $profiles;

	/**
	 * Constructor method.
	 *
	 * Set some global values and create widget.
	 */
	function __construct() {

		/**
		 * Default widget option values.
		 */
		$this->defaults = apply_filters( 'ks_contact_default_styles', array(
			'title'                  => '',
			'shortcodes'			 => 0,
			'about_me'               => '',
			'about_more'             => '',
			'ks_address'             => '',
			'ks_phone'               => '',
			'ks_fax'                 => '',
			'new_window'             => 0,
			'size'                   => 36,
			'border_radius'          => 3,
			'border_width'           => 0,
			'border_color'           => '#ffffff',
			'border_color_hover'     => '#ffffff',
			'icon_color'             => '#ffffff',
			'icon_color_hover'       => '#ffffff',
			'background_color'       => '#999999',
			'background_color_hover' => '#666666',
			'alignment'              => 'alignleft',
			'select_styte'           => 'none',
			'select_background'		 => 'none',
			'bloglovin'              => '',
			'dribbble'               => '',
			'email'                  => '',
			'facebook'               => '',
			'flickr'                 => '',
			'github'                 => '',
			'gplus'                  => '',
			'instagram'              => '',
			'linkedin'               => '',
			'pinterest'              => '',
			'rss'                    => '',
			'stumbleupon'            => '',
			'tumblr'                 => '',
			'twitter'                => '',
			'vimeo'                  => '',
			'youtube'                => '',
			'fttext'				 => '',
		) );

		/**
		 * Social profile glyphs.
		 */
		$this->glyphs = apply_filters( 'ks_contact_default_glyphs', array(
			'bloglovin'		=> '&#xe60c;',
			'dribbble'		=> '&#xe602;',
			'email'			=> '&#xe60d;',
			'facebook'		=> '&#xe606;',
			'flickr'		=> '&#xe609;',
			'github'		=> '&#xe60a;',
			'gplus'			=> '&#xe60e;',
			'instagram' 	=> '&#xe600;',
			'linkedin'		=> '&#xe603;',
			'pinterest'		=> '&#xe605;',
			'rss'			=> '&#xe60b;',
			'stumbleupon'	=> '&#xe601;',
			'tumblr'		=> '&#xe604;',
			'twitter'		=> '&#xe607;',
			'vimeo'			=> '&#xe608;',
			'youtube'		=> '&#xe60f;',
		) );

		/**
		 * Social profile choices.
		 */
		$this->profiles = apply_filters( 'ks_contact_default_profiles', array(
			'bloglovin' => array(
				'label'   => __( 'Bloglovin URI', 'ks-contact-widget' ),
				'pattern' => '<li class="social-bloglovin"><a href="%s" %s>' . $this->glyphs['bloglovin'] . '</a></li>',
			),
			'dribbble' => array(
				'label'   => __( 'Dribbble URI', 'ks-contact-widget' ),
				'pattern' => '<li class="social-dribbble"><a href="%s" %s>' . $this->glyphs['dribbble'] . '</a></li>',
			),
			'email' => array(
				'label'   => __( 'Email URI', 'ks-contact-widget' ),
				'pattern' => '<li class="social-email"><a href="%s" %s>' . $this->glyphs['email'] . '</a></li>',
			),
			'facebook' => array(
				'label'   => __( 'Facebook URI', 'ks-contact-widget' ),
				'pattern' => '<li class="social-facebook"><a href="%s" %s>' . $this->glyphs['facebook'] . '</a></li>',
			),
			'flickr' => array(
				'label'   => __( 'Flickr URI', 'ks-contact-widget' ),
				'pattern' => '<li class="social-flickr"><a href="%s" %s>' . $this->glyphs['flickr'] . '</a></li>',
			),
			'github' => array(
				'label'   => __( 'GitHub URI', 'ks-contact-widget' ),
				'pattern' => '<li class="social-github"><a href="%s" %s>' . $this->glyphs['github'] . '</a></li>',
			),
			'gplus' => array(
				'label'   => __( 'Google+ URI', 'ks-contact-widget' ),
				'pattern' => '<li class="social-gplus"><a href="%s" %s>' . $this->glyphs['gplus'] . '</a></li>',
			),
			'instagram' => array(
				'label'   => __( 'Instagram URI', 'ks-contact-widget' ),
				'pattern' => '<li class="social-instagram"><a href="%s" %s>' . $this->glyphs['instagram'] . '</a></li>',
			),
			'linkedin' => array(
				'label'   => __( 'Linkedin URI', 'ks-contact-widget' ),
				'pattern' => '<li class="social-linkedin"><a href="%s" %s>' . $this->glyphs['linkedin'] . '</a></li>',
			),
			'pinterest' => array(
				'label'   => __( 'Pinterest URI', 'ks-contact-widget' ),
				'pattern' => '<li class="social-pinterest"><a href="%s" %s>' . $this->glyphs['pinterest'] . '</a></li>',
			),
			'rss' => array(
				'label'   => __( 'RSS URI', 'ks-contact-widget' ),
				'pattern' => '<li class="social-rss"><a href="%s" %s>' . $this->glyphs['rss'] . '</a></li>',
			),
			'stumbleupon' => array(
				'label'   => __( 'StumbleUpon URI', 'ks-contact-widget' ),
				'pattern' => '<li class="social-stumbleupon"><a href="%s" %s>' . $this->glyphs['stumbleupon'] . '</a></li>',
			),
			'tumblr' => array(
				'label'   => __( 'Tumblr URI', 'ks-contact-widget' ),
				'pattern' => '<li class="social-tumblr"><a href="%s" %s>' . $this->glyphs['tumblr'] . '</a></li>',
			),
			'twitter' => array(
				'label'   => __( 'Twitter URI', 'ks-contact-widget' ),
				'pattern' => '<li class="social-twitter"><a href="%s" %s>' . $this->glyphs['twitter'] . '</a></li>',
			),
			'vimeo' => array(
				'label'   => __( 'Vimeo URI', 'ks-contact-widget' ),
				'pattern' => '<li class="social-vimeo"><a href="%s" %s>' . $this->glyphs['vimeo'] . '</a></li>',
			),
			'youtube' => array(
				'label'   => __( 'YouTube URI', 'ks-contact-widget' ),
				'pattern' => '<li class="social-youtube"><a href="%s" %s>' . $this->glyphs['youtube'] . '</a></li>',
			),
		) );

		$widget_ops = array(
			'classname'   => 'ks-contact-widget',
			'description' => __( 'Displays social contact & introduce myself.', 'ks-contact-widget' ),
		);

		$control_ops = array(
			'id_base' => 'ks-contact-widget',
		);

		parent::__construct( 'ks-contact-widget', __( 'Ks Contact Widget', 'ks-contact-widget' ), $widget_ops, $control_ops );

		/** Enqueue icon font */
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_css' ) );

		/** Load CSS in <head> */
		add_action( 'wp_head', array( $this, 'css' ) );

		/** Load color picker */
		add_action( 'admin_enqueue_scripts', array( $this, 'load_color_picker' ) );
		add_action( 'admin_footer-widgets.php', array( $this, 'print_scripts' ), 9999 );

		/** Add Noticed when active plugin */
		if ( !defined('I_HAVE_SUPPORTED_THE_CONTACT_WIDGET') )
				add_action( 'admin_notices', array( $this, 'ks_contact_upgrade_nag') );
		add_action( 'network_admin_notices', array( $this, 'ks_contact_upgrade_nag') );

	}

	/**
	 * Color Picker.
	 *
	 * Enqueue the color picker script.
	 *
	 */
	function load_color_picker( $hook ) {
		if( 'widgets.php' != $hook )
			return;
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'underscore' );
	}

	/**
	 * Print scripts.
	 *
	 * Reference https://core.trac.wordpress.org/attachment/ticket/25809/color-picker-widget.php
	 *
	 */
	function print_scripts() {
		?>
		<script>
			( function( $ ){
				function initColorPicker( widget ) {
					widget.find( '.ssiw-color-picker' ).wpColorPicker( {
						change: function ( event ) {
							var $picker = $( this );
							_.throttle(setTimeout(function () {
								$picker.trigger( 'change' );
							}, 5), 250);
						},
						width: 235,
					});
				}

				function onFormUpdate( event, widget ) {
					initColorPicker( widget );
				}

				$( document ).on( 'widget-added widget-updated', onFormUpdate );

				$( document ).ready( function() {
					$( '#widgets-right .widget:has(.ssiw-color-picker)' ).each( function () {
						initColorPicker( $( this ) );
					} );
				} );
			}( jQuery ) );
		</script>
		<?php
	}

	/**
	 * Widget Form.
	 *
	 * Outputs the widget form that allows users to control the output of the widget.
	 *
	 */
	function form( $instance ) {


		/** Merge with defaults */
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'ks-contact-widget' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'about_me' ); ?>"><?php _e( 'About Me:', 'ks-contact-widget' ); ?></label> <textarea class="widefat" name="<?php echo $this->get_field_name('about_me') ?>"><?php echo esc_attr($instance['about_me']) ?></textarea></p>

		<p><label for="<?php echo $this->get_field_id( 'about_more' ); ?>"><?php _e( 'Read More Link:', 'ks-contact-widget' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id( 'about_more' ); ?>" name="<?php echo $this->get_field_name( 'about_more' ); ?>" type="text" value="<?php echo esc_attr( $instance['about_more'] ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'ks_adress' ); ?>"><?php _e( 'Address:', 'ks-contact-widget' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id( 'ks_adress' ); ?>" name="<?php echo $this->get_field_name( 'ks_adress' ); ?>" type="text" value="<?php echo esc_attr( $instance['ks_adress'] ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'ks_phone' ); ?>"><?php _e( 'Phone:', 'ks-contact-widget' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id( 'ks_phone' ); ?>" name="<?php echo $this->get_field_name( 'ks_phone' ); ?>" type="text" value="<?php echo esc_attr( $instance['ks_phone'] ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'ks_fax' ); ?>"><?php _e( 'Fax:', 'ks-contact-widget' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id( 'ks_fax' ); ?>" name="<?php echo $this->get_field_name( 'ks_fax' ); ?>" type="text" value="<?php echo esc_attr( $instance['ks_fax'] ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'ks_twitter' ); ?>"><?php _e( 'Twitter:', 'ks-contact-widget' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id( 'ks_twitter' ); ?>" name="<?php echo $this->get_field_name( 'ks_twitter' ); ?>" type="text" value="<?php echo esc_attr( $instance['ks_twitter'] ); ?>" /></p>

		<hr style="background: #ccc; border: 0; height: 1px; margin: 20px 0;" />

		<p><label><input id="<?php echo $this->get_field_id( 'new_window' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'new_window' ); ?>" value="1" <?php checked( 1, $instance['new_window'] ); ?>/> <?php esc_html_e( 'Open links in new window?', 'ks-contact-widget' ); ?></label></p>

		<p><label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Icon Size', 'ks-contact-widget' ); ?>:</label> <input id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>" type="text" value="<?php echo esc_attr( $instance['size'] ); ?>" size="3" />px</p>

		<p><label for="<?php echo $this->get_field_id( 'border_radius' ); ?>"><?php _e( 'Icon Border Radius:', 'ks-contact-widget' ); ?></label> <input id="<?php echo $this->get_field_id( 'border_radius' ); ?>" name="<?php echo $this->get_field_name( 'border_radius' ); ?>" type="text" value="<?php echo esc_attr( $instance['border_radius'] ); ?>" size="3" />px</p>

		<p><label for="<?php echo $this->get_field_id( 'border_width' ); ?>"><?php _e( 'Border Width:', 'ks-contact-widget' ); ?></label> <input id="<?php echo $this->get_field_id( 'border_width' ); ?>" name="<?php echo $this->get_field_name( 'border_width' ); ?>" type="text" value="<?php echo esc_attr( $instance['border_width'] ); ?>" size="3" />px</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'alignment' ); ?>"><?php _e( 'Alignment', 'ks-contact-widget' ); ?>:</label>
			<select id="<?php echo $this->get_field_id( 'alignment' ); ?>" name="<?php echo $this->get_field_name( 'alignment' ); ?>">
				<option value="alignleft" <?php selected( 'alignright', $instance['alignment'] ) ?>><?php _e( 'Align Left', 'ks-contact-widget' ); ?></option>
				<option value="aligncenter" <?php selected( 'aligncenter', $instance['alignment'] ) ?>><?php _e( 'Align Center', 'ks-contact-widget' ); ?></option>
				<option value="alignright" <?php selected( 'alignright', $instance['alignment'] ) ?>><?php _e( 'Align Right', 'ks-contact-widget' ); ?></option>
			</select>
		</p>

		<p><label for="<?php echo $this->get_field_id('select_style'); ?>" id="<?php echo $this->get_field_id('select_style'); ?>">Select Style</label>
			<select name="<?php echo $this->get_field_name('select_style'); ?>" id="<?php echo $this->get_field_id('select_style'); ?>">
		<?php 
			$select_style = array(
				'none' 		=> 'none', 
				'solid' 	=> 'Solid', 
				'dotted' 	=> 'Dotted', 
				'dashed' 	=> 'Dashed',
				'double' 	=> 'Double',
				'groove' 	=> 'Groove',
				'ridge' 	=> 'Ridge',
				'inset' 	=> 'Intset',
				'outset' 	=> 'Outset',
			); 
			foreach ($select_style as $key => $value) {
		?>
			<option value="<?php echo $key; ?>"<?php selected( $instance['select_style'], $key ); ?>><?php _e($value, 'ks-contact-widget'); ?></option>
		<?php
		}
		?>
			</select>
		</p>

		<p><label for="<?php echo $this->get_field_id('select_background'); ?>" id="<?php echo $this->get_field_id('select_background'); ?>">Select Background</label>
			<select name="<?php echo $this->get_field_name('select_background'); ?>" id="<?php echo $this->get_field_id('select_background'); ?>">
		<?php 
			$select_background = array(
				'none' 		=> 'none', 
				'bg_style_1' 	=> 'Style 1', 
				'bg_style_2' 	=> 'Style 2', 
				'bg_style_3' 	=> 'Style 3',
				'bg_style_4' 	=> 'Style 4',
				'bg_style_5' 	=> 'Style 5',
				'bg_style_6' 	=> 'Style 6',
				'bg_style_7' 	=> 'Style 7',
				'bg_style_8' 	=> 'Style 8',
				'bg_style_9' 	=> 'Style 9',
				'bg_style_10' 	=> 'Style 10',
				'bg_style_11' 	=> 'Style 11',
				'bg_style_12' 	=> 'Style 12',
				'bg_style_13' 	=> 'Style 13',
				'bg_style_14' 	=> 'Style 14',
				'bg_style_15' 	=> 'Style 15',
				'bg_style_16' 	=> 'Style 16',
				'bg_style_17' 	=> 'Style 17',
				'bg_style_18' 	=> 'Style 18',
				'bg_style_19' 	=> 'Style 19',
				'bg_style_20' 	=> 'Style 20',
				'bg_style_21' 	=> 'Style 21',
				'bg_style_22' 	=> 'Style 22',
				'bg_style_23' 	=> 'Style 23',
				'bg_style_24' 	=> 'Style 24',
			); 
			foreach ($select_background as $key => $value) {
		?>
			<option value="<?php echo $key; ?>"<?php selected( $instance['select_background'], $key ); ?>><?php _e($value, 'ks-contact-widget'); ?></option>
		<?php
		}
		?>
			</select>
		</p>

		<hr style="background: #ccc; border: 0; height: 1px; margin: 20px 0;" />

		<p><label for="<?php echo $this->get_field_id( 'background_color' ); ?>"><?php _e( 'Icon Font Color:', 'ks-contact-widget' ); ?></label><br /> <input id="<?php echo $this->get_field_id( 'icon_color' ); ?>" name="<?php echo $this->get_field_name( 'icon_color' ); ?>" type="text" class="ssiw-color-picker" data-default-color="<?php echo esc_attr( $this->defaults['icon_color'] ); ?>" value="<?php echo esc_attr( $instance['icon_color'] ); ?>" size="6" /></p>

		<p><label for="<?php echo $this->get_field_id( 'background_color_hover' ); ?>"><?php _e( 'Icon Font Hover Color:', 'ks-contact-widget' ); ?></label><br /> <input id="<?php echo $this->get_field_id( 'icon_color_hover' ); ?>" name="<?php echo $this->get_field_name( 'icon_color_hover' ); ?>" type="text" class="ssiw-color-picker" data-default-color="<?php echo esc_attr( $this->defaults['icon_color_hover'] ); ?>" value="<?php echo esc_attr( $instance['icon_color_hover'] ); ?>" size="6" /></p>

		<p><label for="<?php echo $this->get_field_id( 'background_color' ); ?>"><?php _e( 'Background Color:', 'ks-contact-widget' ); ?></label><br /> <input id="<?php echo $this->get_field_id( 'background_color' ); ?>" name="<?php echo $this->get_field_name( 'background_color' ); ?>" type="text" class="ssiw-color-picker" data-default-color="<?php echo esc_attr( $this->defaults['background_color'] ); ?>" value="<?php echo esc_attr( $instance['background_color'] ); ?>" size="6" /></p>

		<p><label for="<?php echo $this->get_field_id( 'background_color_hover' ); ?>"><?php _e( 'Background Hover Color:', 'ks-contact-widget' ); ?></label><br /> <input id="<?php echo $this->get_field_id( 'background_color_hover' ); ?>" name="<?php echo $this->get_field_name( 'background_color_hover' ); ?>" type="text" class="ssiw-color-picker" data-default-color="<?php echo esc_attr( $this->defaults['background_color_hover'] ); ?>" value="<?php echo esc_attr( $instance['background_color_hover'] ); ?>" size="6" /></p>

		<p><label for="<?php echo $this->get_field_id( 'border_color' ); ?>"><?php _e( 'Border Color:', 'ks-contact-widget' ); ?></label><br /> <input id="<?php echo $this->get_field_id( 'border_color' ); ?>" name="<?php echo $this->get_field_name( 'border_color' ); ?>" type="text" class="ssiw-color-picker" data-default-color="<?php echo esc_attr( $this->defaults['border_color'] ); ?>" value="<?php echo esc_attr( $instance['border_color'] ); ?>" size="6" /></p>

		<p><label for="<?php echo $this->get_field_id( 'border_color_hover' ); ?>"><?php _e( 'Border Hover Color:', 'ks-contact-widget' ); ?></label><br /> <input id="<?php echo $this->get_field_id( 'border_color_hover' ); ?>" name="<?php echo $this->get_field_name( 'border_color_hover' ); ?>" type="text" class="ssiw-color-picker" data-default-color="<?php echo esc_attr( $this->defaults['border_color_hover'] ); ?>" value="<?php echo esc_attr( $instance['border_color_hover'] ); ?>" size="6" /></p>

		<hr style="background: #ccc; border: 0; height: 1px; margin: 20px 0;" />

		<?php
		foreach ( (array) $this->profiles as $profile => $data ) {

			printf( '<p><label for="%s">%s:</label></p>', esc_attr( $this->get_field_id( $profile ) ), esc_attr( $data['label'] ) );
			printf( '<p><input type="text" id="%s" name="%s" value="%s" class="widefat" />', esc_attr( $this->get_field_id( $profile ) ), esc_attr( $this->get_field_name( $profile ) ), esc_url( $instance[$profile] ) );
			printf( '</p>' );

		}
		?>

		<p><label for="<?php echo $this->get_field_id( 'fttext' ); ?>"><?php _e( 'Footer Text:', 'ks-contact-widget' ); ?></label> <textarea class="widefat" name="<?php echo $this->get_field_name('fttext') ?>"><?php echo esc_attr($instance['fttext']) ?></textarea></p>
	
	<?php
	}

	/**
	 * Form validation and sanitization.
	 *
	 * Runs when you save the widget form. Allows you to validate or sanitize widget options before they are saved.
	 *
	 */
	function update( $newinstance, $oldinstance ) {

		foreach ( $newinstance as $key => $value ) {

			/** Border radius and Icon size must not be empty, must be a digit */
			if ( ( 'border_radius' == $key || 'size' == $key ) && ( '' == $value || ! ctype_digit( $value ) ) ) {
				$newinstance[$key] = 0;
			}

			if ( ( 'border_width' == $key || 'size' == $key ) && ( '' == $value || ! ctype_digit( $value ) ) ) {
				$newinstance[$key] = 0;
			}

			/** Validate hex code colors */
			elseif ( strpos( $key, '_color' ) && 0 == preg_match( '/^#(([a-fA-F0-9]{3}$)|([a-fA-F0-9]{6}$))/', $value ) ) {
				$newinstance[$key] = $oldinstance[$key];
			}

			/** Sanitize Profile URIs */
			elseif ( array_key_exists( $key, (array) $this->profiles ) ) {
				$newinstance[$key] = esc_url( $newinstance[$key] );
			}

		}

		return $newinstance;

	}

	/**
	 * Widget Output.
	 *
	 * Outputs the actual widget on the front-end based on the widget options the user selected.
	 *
	 */
	function widget( $args, $instance ) {

		extract( $args );

		/** Merge with defaults */
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $before_widget.'<div class="'.$instance['select_style'].' '.$instance['select_background'].'">';

			if ( ! empty( $instance['title'] ) )
				echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;

			$before_contact_details = '<p class="highlight">';
			$end_contact_details = '</p>';

			if ( ! empty( $instance['about_me'] ) )
				echo apply_filters( 'widget_about_me', $instance['about_me'], $instance, $this->id_base );

			if ( ! empty( $instance['about_more'] ) )
				echo apply_filters( 'widget_about_more', '...<a href="'.$instance['about_more'].'">Read more</a>', $instance, $this->id_base );

			if ( ! empty( $instance['ks_adress'] ) )
				echo $before_contact_details . apply_filters( 'widget_ks_adress', '<span class="fa fa-map-marker"> '.$instance['ks_adress'].'</span>', $instance, $this->id_base ) . $end_contact_details;

			if ( ! empty( $instance['ks_phone'] ) )
				echo $before_contact_details . apply_filters( 'widget_ks_phone', '<span class="fa fa-phone"> <a href="tel:'.$instance['ks_phone'].'">'.$instance['ks_phone'].'</a></span>', $instance, $this->id_base ) . $end_contact_details;

			if ( ! empty( $instance['ks_fax'] ) )
				echo $before_contact_details . apply_filters( 'widget_ks_fax', '<span class="fa fa-fax"> '.$instance['ks_fax'].'</span>', $instance, $this->id_base ) . $end_contact_details;

			if ( ! empty( $instance['ks_twitter'] ) )
				echo $before_contact_details . apply_filters( 'widget_ks_twitter', '<span class="fa fa-twitter"> '.$instance['ks_twitter'].'</span>', $instance, $this->id_base ) . $end_contact_details;

			$output = '';

			$new_window = $instance['new_window'] ? 'target="_blank"' : '';

			$profiles = (array) $this->profiles;

			foreach ( $profiles as $profile => $data ) {

				if ( empty( $instance[ $profile ] ) )
					continue;

				if ( is_email( $instance[ $profile ] ) )
					$output .= sprintf( $data['pattern'], 'mailto:' . esc_attr( $instance[$profile] ), $new_window );
				else
					$output .= sprintf( $data['pattern'], esc_url( $instance[$profile] ), $new_window );

			}


			if ( $output )
				printf( '<ul class="%s">%s</ul>', $instance['alignment'], $output );

			if ( ! empty( $instance['fttext'] ) )
				echo '<div class="fttext"><p>'.apply_filters( 'widget_fttext', $instance['fttext'], $instance, $this->id_base ).'</p></div>';

		echo '</div>'.$after_widget;

	}

	function enqueue_css() {

		$cssfile	= apply_filters( 'ks_contact_default_css', plugin_dir_url( __FILE__ ) . 'css/style.css' );

		wp_enqueue_style( 'ks-contact-widget-font', esc_url( $cssfile ), array(), '1.0.12', 'all' );
	}

	/**
	 * Custom CSS.
	 *
	 * Outputs custom CSS to control the look of the icons.
	 */
	function css() {

		/** Pull widget settings, merge with defaults */
		$all_instances = $this->get_settings();
		if ( ! isset( $this->number ) || ! isset( $all_instances[$this->number] ) ) {
			return;
		}

		$instance = wp_parse_args( $all_instances[$this->number], $this->defaults );

		$font_size = round( (int) $instance['size'] / 2 );
		$icon_padding = round ( (int) $font_size / 2 );

		/** The CSS to output */
		$css = '
		.ks-contact-widget ul li a,
		.ks-contact-widget ul li a:hover {
			background-color: ' . $instance['background_color'] . ' !important;
			border-radius: ' . $instance['border_radius'] . 'px;
			color: ' . $instance['icon_color'] . ' !important;
			border: ' . $instance['border_width'] . 'px ' . $instance['border_color'] . ' solid !important;
			font-size: ' . $font_size . 'px;
			padding: ' . $icon_padding . 'px;
		}

		.ks-contact-widget ul li a:hover {
			background-color: ' . $instance['background_color_hover'] . ' !important;
			border-color: ' . $instance['border_color_hover'] . ' !important;
			color: ' . $instance['icon_color_hover'] . ' !important;
		}';

		/** Minify a bit */
		$css = str_replace( "\t", '', $css );
		$css = str_replace( array( "\n", "\r" ), ' ', $css );

		/** Echo the CSS */
		echo '<style type="text/css" media="screen">' . $css . '</style>';

	}

}

add_action( 'widgets_init', 'ks_ssiw_load_widget' );
/**
 * Widget Registration.
 *
 * Register Simple Social Icons widget.
 *
 */
function ks_ssiw_load_widget() {

	register_widget( 'KS_Contact_Widget_Widget' );

}
<?php
add_action('wp_enqueue_scripts', 'chilly_theme_css', 999);
function chilly_theme_css() {
    wp_enqueue_style( 'chilly-parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style('chilly-child-style',get_stylesheet_directory_uri() . '/style.css',array('parent-style'));
	wp_enqueue_style('bootstrap', ST_TEMPLATE_DIR . '/css/bootstrap.css');
	wp_enqueue_style('default-style-css', get_stylesheet_directory_uri()."/css/default.css" );
	wp_enqueue_style('theme-menu-style', get_stylesheet_directory_uri().'/css/theme-menu.css');
	wp_enqueue_style('media-responsive-css', get_stylesheet_directory_uri()."/css/media-responsive.css" );
	wp_dequeue_style('default-css', get_template_directory_uri() .'/css/default.css');   
}

if ( ! function_exists( 'chilly_theme_setup' ) ) :

function chilly_theme_setup() {

//Load text domain for translation-ready
load_theme_textdomain('chilly', get_stylesheet_directory() . '/languages');

require( get_stylesheet_directory() . '/functions/chilly-info/welcome-screen.php' );

require( get_stylesheet_directory() . '/functions/customizer/customizer_general_settings.php' );

}
endif; 
add_action( 'after_setup_theme', 'chilly_theme_setup' );


/**
 * Import options from SpicePress
 *
 */
function chilly_get_lite_options() {
	$spicepress_mods = get_option( 'theme_mods_spicepress' );
	if ( ! empty( $spicepress_mods ) ) {
		foreach ( $spicepress_mods as $spicepress_mod_k => $spicepress_mod_v ) {
			set_theme_mod( $spicepress_mod_k, $spicepress_mod_v );
		}
	}
}
add_action( 'after_switch_theme', 'chilly_get_lite_options' );

//Ragiter Customize
if ( ! function_exists( 'chilly_slider_customize_register' ) ) :
function chilly_slider_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	

/* Slider Section */
	$wp_customize->add_section( 'slider_section' , array(
		'title'      => __('Slider settings', 'chilly'),
		'panel'  => 'section_settings',
		'priority'   => 1,
   	) );
		
		// Enable slider
		$wp_customize->add_setting( 'home_page_slider_enabled' , array( 'default' => 'on', 'sanitize_callback' => 'chilly_sanitize_select',) );
		$wp_customize->add_control(	'home_page_slider_enabled' , array(
				'label'    => __( 'Enable slider', 'chilly' ),
				'section'  => 'slider_section',
				'type'     => 'radio',
				'choices' => array(
					'on'=>__('ON', 'chilly'),
					'off'=>__('OFF', 'chilly')
				)
		));
		
		
		//Slider Image
		$wp_customize->add_setting( 'home_slider_image',array('default' => get_stylesheet_directory_uri() .'/images/slider.jpg',
		'sanitize_callback' => 'esc_url_raw', 'transport' => $selective_refresh,));
 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'home_slider_image',
				array(
					'type'        => 'upload',
					'label' => __('Image','chilly'),
					'settings' =>'home_slider_image',
					'section' => 'slider_section',
					
				)
			)
		);
		
		// Image overlay
		$wp_customize->add_setting( 'slider_image_overlay', array(
			'default' => true,
			'sanitize_callback' => 'chilly_sanitize_checkbox',
		) );
		
		$wp_customize->add_control('slider_image_overlay', array(
			'label'    => __('Enable slider image overlay', 'chilly' ),
			'section'  => 'slider_section',
			'type' => 'checkbox',
		) );
		
		
		//Slider Background Overlay Color
		$wp_customize->add_setting( 'slider_overlay_section_color', array(
			'sanitize_callback' => 'chilly_sanitize_rgba',
			'default' => 'rgba(0,0,0,0.30)',
            ) );	
            
            $wp_customize->add_control(new SpicePress_Customize_Alpha_Color_Control( $wp_customize,'slider_overlay_section_color', array(
               'label'      => __('Slider image overlay color','chilly' ),
                'palette' => true,
                'section' => 'slider_section')
            ) );
		
		
		// Slider title
		$wp_customize->add_setting( 'home_slider_title',array(
		'default' => __('Welcome to Chilly Theme','chilly'),
		'sanitize_callback' => 'chilly_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'home_slider_title',array(
		'label'   => __('Title','chilly'),
		'section' => 'slider_section',
		'type' => 'text',
		));	
		
		//Slider discription
		$wp_customize->add_setting( 'home_slider_discription',array(
		'default' => 'Sea summo mazim ex, ea errem eleifend definitionem vim. Ut nec hinc dolor possim mei ludus efficiendi ei sea summo mazim ex.',
		'sanitize_callback' => 'chilly_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'home_slider_discription',array(
		'label'   => __('Description','chilly'),
		'section' => 'slider_section',
		'type' => 'textarea',
		));
		
		
		// Slider button text
		$wp_customize->add_setting( 'home_slider_btn_txt',array(
		'default' => __('Read more','chilly'),
		'sanitize_callback' => 'chilly_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'home_slider_btn_txt',array(
		'label'   => __('Button Text','chilly'),
		'section' => 'slider_section',
		'type' => 'text',
		));
		
		// Slider button link
		$wp_customize->add_setting( 'home_slider_btn_link',array(
		'default' => __('#','chilly'),
		'sanitize_callback' => 'chilly_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'home_slider_btn_link',array(
		'label'   => __('Button Link','chilly'),
		'section' => 'slider_section',
		'type' => 'text',
		));
		
		// Slider button target
		$wp_customize->add_setting(
		'home_slider_btn_target', 
			array(
			'default'        => false,
			'sanitize_callback' => 'chilly_sanitize_checkbox',
		));
		$wp_customize->add_control('home_slider_btn_target', array(
			'label'   => __('Open link in new tab', 'chilly'),
			'section' => 'slider_section',
			'type' => 'checkbox',
		));
		
		
		
}

add_action( 'customize_register', 'chilly_slider_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function chilly_register_home_slider_section_partials( $wp_customize ){

	
	
	$wp_customize->selective_refresh->add_partial( 'home_slider_image', array(
		'selector'            => '.slider .item',
		'settings'            => 'home_slider_image',
	
	) );
	
	//Slider section
	$wp_customize->selective_refresh->add_partial( 'home_slider_title', array(
		'selector'            => '.format-standard .slide-text-bg1 h1',
		'settings'            => 'home_slider_title',
		'render_callback'  => 'chilly_slider_section_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'home_slider_discription', array(
		'selector'            => '.format-standard .slide-text-bg1 p',
		'settings'            => 'home_slider_discription',
		'render_callback'  => 'chilly_slider_section_discription_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'home_slider_btn_txt', array(
		'selector'            => '.slide-btn-sm',
		'settings'            => 'home_slider_btn_txt',
		'render_callback'  => 'chilly_slider_btn_render_callback',
	
	) );
}

add_action( 'customize_register', 'chilly_register_home_slider_section_partials' );


function chilly_slider_section_title_render_callback() {
	return get_theme_mod( 'home_slider_title' );
}

function chilly_slider_section_discription_render_callback() {
	return get_theme_mod( 'home_slider_discription' );
}

function chilly_slider_btn_render_callback() {
	return get_theme_mod( 'home_slider_btn_txt' );
}

function chilly_home_page_sanitize_text( $input ) {

		return wp_kses_post( force_balance_tags( $input ) );

}

/* Read more on post*/
function chilly_excerpt_more( $more ) {
	return '<p><a href="' . esc_url(get_permalink()) . '" class="more-link">'.__('Read More','chilly').'</a></p>';
}
add_filter( 'excerpt_more', 'chilly_excerpt_more', 20 );


function chilly_general_settings_customizer( $wp_customize ){

/* Remove animation */
	$wp_customize->add_section( 'banner_image_setting', array(
		'title'      => __('Banner setting','chilly'),
		'panel'  => 'general_settings',
   	) );
	
	
		// Banner Image remove
		$wp_customize->add_setting( 'remove_banner_image',array(
		'capability'     => 'edit_theme_options',
		'default' => false,
		'sanitize_callback' => 'chilly_sanitize_checkbox',
		));	
		$wp_customize->add_control( 'remove_banner_image',array(
		'label'   => __('Banner image remove from all pages','chilly'),
		'section' => 'banner_image_setting',
		'type' => 'checkbox',
		));
}
add_action( 'customize_register', 'chilly_general_settings_customizer' );


//Remove Banner Image
function chilly_banner_image()
{
$remove_banner_image = get_theme_mod('remove_banner_image',false);
if($remove_banner_image !=true)
{
 get_template_part('index','slider');	
}
}


if ( ! function_exists( 'chilly_sanitize_checkbox' ) ) :

	/**
	 * Sanitize checkbox.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $checked Whether the checkbox is checked.
	 * @return bool Whether the checkbox is checked.
	 */
	function chilly_sanitize_checkbox( $checked ) {

		return ( ( isset( $checked ) && true === $checked ) ? true : false );
	}

endif;


if ( ! function_exists( 'chilly_sanitize_select' ) ) :

	/**
	 * Sanitize select.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed                $input The value to sanitize.
	 * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
	 * @return mixed Sanitized value.
	 */
	function chilly_sanitize_select( $input, $setting ) {

		$input = sanitize_text_field( $input );

		// Get list of choices from the control associated with the setting.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// If the input is a valid key, return it; otherwise, return the default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

endif;

			/**
			 * Sanitize rgba color.
			 *
			 * @param string $value Color in rgba format.
			 *
			 * @return string
			 */
function chilly_sanitize_rgba( $value ) {
				
		$red   = 'rgba(0,0,0,0)';
		$green = 'rgba(0,0,0,0)';
		$blue  = 'rgba(0,0,0,0)';
		$alpha = 'rgba(0,0,0,0)';   // If empty or an array return transparent
		if ( empty( $value ) || is_array( $value ) ) {
			return '';
		}

		// By now we know the string is formatted as an rgba color so we need to further sanitize it.
		$value = str_replace( ' ', '', $value );
		sscanf( $value, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
}
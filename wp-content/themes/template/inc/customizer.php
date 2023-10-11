<?php
/**
 * template Theme Customizer
 *
 * @package template
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function template_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'template_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'template_customize_partial_blogdescription',
			)
		);
	}

	$wp_customize->add_setting('footer_logo');
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'footer_logo',
			array(
				'label' => 'Footer logo',
				'section' => 'title_tagline',
				'settings' => 'footer_logo',
				'priority' => 8
			)
		)
	);
}
add_action( 'customize_register', 'template_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function template_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function template_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function template_customize_preview_js() {
	wp_enqueue_script( 'template-customizer', get_template_directory_uri() . '/inc/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'template_customize_preview_js' );

<?php
/**
 * template functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package template
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function template_setup()
{
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on template, use a find and replace
	 * to change 'template' to the name of your theme in all the template files.
	 */
	load_theme_textdomain('template', get_template_directory() . '/languages');

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support('title-tag');

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'primary' => esc_html__('Primary', 'template'),
		)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height' => 250,
			'width' => 250,
			'flex-width' => true,
			'flex-height' => true,
		)
	);

	/**
	 * Removing rendered svg filters
	 */
	remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
}
add_action('after_setup_theme', 'template_setup');

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function template_widgets_init()
{
	register_sidebar(
		array(
			'name' => esc_html__('Sidebar', 'template'),
			'id' => 'sidebar-1',
			'description' => esc_html__('Add widgets here.', 'template'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		)
	);
}
add_action('widgets_init', 'template_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function template_scripts()
{
	wp_enqueue_style('template-style', get_template_directory_uri() . '/dist/app.css', array(), _S_VERSION);

	wp_enqueue_script('template-scripts', get_template_directory_uri() . '/dist/bundle.js', array(), _S_VERSION, true);

	// if (is_singular() && comments_open() && get_option('thread_comments')) {
	// 	wp_enqueue_script('comment-reply');
	// }
}
add_action('wp_enqueue_scripts', 'template_scripts');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Enable SVG support.
 */
add_filter('wp_check_filetype_and_ext', function ($checked, $file, $filename, $mimes) {
	if (!$checked['type']) {
		$check_filetype = wp_check_filetype($filename, $mimes);
		$ext = $check_filetype['ext'];
		$type = $check_filetype['type'];
		$proper_filename = $filename;
		if ($type && 0 === strpos($type, 'image/') && $ext !== 'svg') {
			$ext = $type = false;
		}
		$checked = compact('ext', 'type', 'proper_filename');
	}
	return $checked;
}, 10, 4);
add_filter('upload_mimes', 'svg_mime_types', 99);
function svg_mime_types($mimes = array())
{
	if (current_user_can('administrator')) {
		$mimes['svg'] = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
		return $mimes;
	} else {
		return $mimes;
	}
}

/**
 * Remove auto p (CF7)
 */
add_filter('wpcf7_autop_or_not', '__return_false');

/**
 * Social menu walker
 */
class Social_Menu_Walker extends Walker_Nav_Menu
{
	function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
	{
		$output .= '<li class="' . implode(" ", $item->classes) . '">';
		$output .= '<a href="' . $item->url . '">';
		switch ($item->title) {
			case 'Facebook':
				$output .= '<svg xmlns="http://www.w3.org/2000/svg" width="10.713" height="20.514" viewBox="0 0 10.713 20.514"><title>Facebook</title><path d="M86.952,20.514V11.169h3.191L90.6,7.522H86.952V5.242c0-1.026.342-1.823,1.823-1.823h1.937V.114c-.456,0-1.6-.114-2.849-.114a4.4,4.4,0,0,0-4.673,4.787V7.522H80v3.647h3.191v9.345Z" transform="translate(-80)" fill="#fff" fill-rule="evenodd"/></svg>';
				break;
			case 'Instagram':
				$output .= '<svg xmlns="http://www.w3.org/2000/svg" width="20.514" height="20.514" viewBox="0 0 20.514 20.514"><title>Instagram</title><path d="M10.257,1.823a31.45,31.45,0,0,1,4.1.114,5.286,5.286,0,0,1,1.937.342,4,4,0,0,1,1.937,1.937,5.286,5.286,0,0,1,.342,1.937c0,1.026.114,1.368.114,4.1a31.449,31.449,0,0,1-.114,4.1,5.286,5.286,0,0,1-.342,1.937A4,4,0,0,1,16.3,18.234a5.286,5.286,0,0,1-1.937.342c-1.026,0-1.368.114-4.1.114a31.449,31.449,0,0,1-4.1-.114,5.286,5.286,0,0,1-1.937-.342A4,4,0,0,1,2.279,16.3a5.286,5.286,0,0,1-.342-1.937c0-1.026-.114-1.368-.114-4.1a31.45,31.45,0,0,1,.114-4.1,5.286,5.286,0,0,1,.342-1.937,4.093,4.093,0,0,1,.8-1.14,1.927,1.927,0,0,1,1.14-.8,5.286,5.286,0,0,1,1.937-.342,31.45,31.45,0,0,1,4.1-.114m0-1.823A33.673,33.673,0,0,0,6.04.114,7.036,7.036,0,0,0,3.533.57a4.461,4.461,0,0,0-1.823,1.14A4.461,4.461,0,0,0,.57,3.533,5.193,5.193,0,0,0,.114,6.04,33.673,33.673,0,0,0,0,10.257a33.673,33.673,0,0,0,.114,4.217A7.036,7.036,0,0,0,.57,16.981,4.461,4.461,0,0,0,1.709,18.8a4.461,4.461,0,0,0,1.823,1.14A7.036,7.036,0,0,0,6.04,20.4a33.674,33.674,0,0,0,4.217.114,33.674,33.674,0,0,0,4.217-.114,7.036,7.036,0,0,0,2.507-.456,4.781,4.781,0,0,0,2.963-2.963,7.036,7.036,0,0,0,.456-2.507c0-1.14.114-1.482.114-4.217A33.674,33.674,0,0,0,20.4,6.04a7.036,7.036,0,0,0-.456-2.507A4.461,4.461,0,0,0,18.8,1.709,4.461,4.461,0,0,0,16.981.57,7.036,7.036,0,0,0,14.474.114,33.673,33.673,0,0,0,10.257,0m0,5.014a5.158,5.158,0,0,0-5.242,5.242,5.242,5.242,0,1,0,5.242-5.242m0,8.661a3.358,3.358,0,0,1-3.419-3.419,3.358,3.358,0,0,1,3.419-3.419,3.358,3.358,0,0,1,3.419,3.419,3.358,3.358,0,0,1-3.419,3.419m5.47-10.143a1.254,1.254,0,1,0,1.254,1.254,1.265,1.265,0,0,0-1.254-1.254" fill="#fff" fill-rule="evenodd"/></svg>';
				break;
			case 'LinkedIn':
				$output .= '<svg xmlns="http://www.w3.org/2000/svg" width="20.514" height="20.514" viewBox="0 0 20.514 20.514"><title>LinkedIn</title><path d="M4.592,20.524H.339V6.828H4.592ZM2.463,4.959A2.475,2.475,0,1,1,4.926,2.473,2.484,2.484,0,0,1,2.463,4.959ZM20.51,20.524H16.266V13.857c0-1.589-.032-3.627-2.211-3.627-2.211,0-2.55,1.726-2.55,3.512v6.782H7.256V6.828h4.079V8.7h.06a4.469,4.469,0,0,1,4.024-2.212c4.3,0,5.1,2.834,5.1,6.516v7.523Z" transform="translate(0 -0.01)" fill="#fff"/></svg>';
				break;
			default:
				$output .= $item->title;
		}
		$output .= '</a>';
		$output .= '</li>';
	}
}

/**
 * Remove author schema
 */
add_filter('wpseo_schema_needs_author', '__return_false');
function template_remove_author_wpseo_article_schema($graph_piece)
{
	unset($graph_piece['author']);
	return $graph_piece;
}
add_filter('wpseo_schema_article', 'template_remove_author_wpseo_article_schema');

/**
 * Remove the admin bar on front end
 */
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar()
{
	if (!is_admin()) {
		show_admin_bar(false);
	}
}

/**
 * disable XMLRPC
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Plugin permissions
 */
add_filter('flamingo_map_meta_cap', function ($meta_caps) {
	$meta_caps = array_merge(
		$meta_caps,
		array(
			'flamingo_edit_inbound_message' => 'edit_pages',
			'flamingo_edit_inbound_messages' => 'edit_pages',
		)
	);

	return $meta_caps;
});

add_filter('sgo_purge_button_capabilities', 'sgo_add_new_role');
function sgo_add_new_role($default_capabilities)
{
	// Allow new user role to flush cache.
	$default_capabilities[] = 'delete_others_posts'; // For Editors.
	$default_capabilities[] = 'edit_published_posts'; // For Authors.

	return $default_capabilities;
}
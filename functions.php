<?php
/**
 * Hello Elementor Child theme functions and definitions
 * This is the child theme for Hello Elementor theme.
 *
 * @link https://github.com/arianomrani/hello-elementor-child
 *
 * @package Hello Elementor Child
 */

// define theme styles
function hello_elementor_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_styles' );

// remove wp version number from scripts and styles 
function remove_css_js_version( $src ) {
    if( strpos( $src, '?ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'remove_css_js_version', 9999 );
add_filter( 'script_loader_src', 'remove_css_js_version', 9999 );

// remove wp version number from head and rss and admin foter
function wp_remove_version() {
    return '';
}
add_filter( 'the_generator', 'wp_remove_version' );
add_filter( 'update_footer', 'wp_remove_version', 9999 );

//Remove Site Health
function themeprefix_remove_dashboard_widget() {
    remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
}
function remove_site_health_menu(){
  remove_submenu_page( 'tools.php','site-health.php' ); 
}
add_action( 'admin_menu', 'remove_site_health_menu' );
add_action( 'wp_dashboard_setup', 'themeprefix_remove_dashboard_widget' );
add_filter( 'wp_fatal_error_handler_enabled', '__return_false' );

// leazy load
function deactivate_on_page() {
    if ( is_page() ) {
        add_filter( 'do_rocket_lazyload', '__return_false' );
    }
}
add_filter( 'wp', 'deactivate_on_page' );

// Begin Custom Scripting to Move JavaScript from the Head to the Footer
function remove_head_scripts() {
	remove_action('wp_head', 'wp_print_scripts');
	remove_action('wp_head', 'wp_print_head_scripts', 9);
	remove_action('wp_head', 'wp_enqueue_scripts', 1);
	add_action('wp_footer', 'wp_print_scripts', 5);
	add_action('wp_footer', 'wp_print_head_scripts', 5);
	add_action('wp_footer', 'wp_enqueue_scripts', 5);
}
add_action( 'wp_enqueue_scripts', 'remove_head_scripts' );

// hide different links when displaying blog posts (next, previous, short URL)
remove_action( 'wp_head', 'start_post_rel_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link' );

remove_action( 'wp_head', 'feed_links_extra', 3 ); // removes links to rss categories
remove_action( 'wp_head', 'feed_links', 2 ); // minus links to the main rss and comments
remove_action( 'wp_head', 'rsd_link' ); // Really Simple Discovery service
remove_action( 'wp_head', 'wlwmanifest_link' ); // Windows Live Writer
remove_action( 'wp_head', 'wp_generator' ); // hide the wordpress version

//Removal of JSON API links
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links');
remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );

// remove emoji
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );

?>
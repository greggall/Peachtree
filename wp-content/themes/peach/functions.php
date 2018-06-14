<?php

function peach_theme_setup() {
    // This theme uses wp_nav_menu() in Detail page template and for booking pages.
    register_nav_menus( array(
        'detail'    => __( 'Detail Pages', 'peach'),
        'booking'   => __( 'Booking Pages', 'peach'),
    ));
}
add_action('after_setup_theme', 'peach_theme_setup');
/* Add bootstrap support to the Wordpress theme*/

function cyber_dev_add() {
	wp_enqueue_style( 'awesome-css', get_stylesheet_directory_uri() . '/font-awesome.min.css' );
	wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/js/custom.js', array(), '1.0', true );
}

add_action( 'wp_enqueue_scripts', 'cyber_dev_add' );
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
function enqueue_parent_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri().'/style.css');
    wp_enqueue_script('ddaccordion', get_stylesheet_directory_uri() . '/js/ddaccordion.js', array ('jquery'), 1.0, true);
    wp_enqueue_script('jquery_block_ui', get_stylesheet_directory_uri() . '/js/jQueryBlockUI.js', array ('jquery'), 1.0, true);
}

// Overwrite Navigation js with Parrent Theme
add_action('wp_enqueue_scripts', 'overwrite_script_with_parrent_theme', 100);
function overwrite_script_with_parrent_theme()
{
	wp_dequeue_script('twentyseventeen-navigation');
	wp_enqueue_script('peach_script_handle', get_stylesheet_directory_uri().'/js/navigation.js', array('jquery'), 1.0, true);
}
// Adds Page Template Shared Sidebar widget area.
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Shared Page Template Sidebar',
        'id' => 'custom-page-template-sidebar-shared',
        'description' => 'Add widgets here to appear in your custom template pages sidebar.',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>'
    ));
}

// Adds Reviews Page Template Sidebar widget area.
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Reviews Page Template Sidebar',
        'id' => 'custom-page-template-sidebar-reviews',
        'description' => 'Add widgets here to appear in your reviews pages sidebar.',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>'
    ));
}

// Adds Urgent Care Page Template Sidebar widget area.
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Urgent Care Page Template Sidebar',
        'id' => 'custom-page-template-sidebar-urgent-care',
        'description' => 'Add widgets here to appear in your urgent care pages sidebar.',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>'
    ));
}

// Adds Employers Page Template Sidebar widget area.
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Employers Page Template Sidebar',
        'id' => 'custom-page-template-sidebar-employers',
        'description' => 'Add widgets here to appear in your employers pages sidebar.',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>'
    ));
}

// Adds Reviews Page Template Sidebar widget area.
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Billing Info Page Template Sidebar',
        'id' => 'custom-page-template-sidebar-billing-info',
        'description' => 'Add widgets here to appear in your billing info pages sidebar.',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>'
    ));
}

// Adds About Page Template Sidebar widget area.
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'About Page Template Sidebar',
        'id' => 'custom-page-template-sidebar-about',
        'description' => 'Add widgets here to appear in your about pages sidebar.',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>'
    ));
}

// Adds Contact Us Page Template Sidebar widget area.
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Contact Us Page Template Sidebar',
        'id' => 'custom-page-template-sidebar-contact-us',
        'description' => 'Add widgets here to appear in your contact us pages sidebar.',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>'
    ));
}

// Adds header top widget area.
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Header Top Widget Area',
        'id' => 'header-top-extra-widget-area',
        'description' => 'Extra widget area at the top of the header',
        'before_widget' => '<div class="widget header-top-extra-widget">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>'
    ));
}

// Adds header widget area.
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Header Widget Area',
        'id' => 'header-extra-widget-area',
        'description' => 'Extra widget area after the header image',
        'before_widget' => '<div class="widget header-extra-widget">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>'
    ));
}

// Adds Footer 3 widget area.
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Footer 3',
        'id' => 'footer3-extra-widget-area',
        'description' => 'Add widgets here to appear in your footer.',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>'
    ));
}

// Adds Footer Top widget area.
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Footer Top',
        'id' => 'footer-top-extra-widget-area',
        'description' => 'Add widgets here to appear in your footer at the top.',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>'
    ));
}

// Remove widget titles
add_filter('widget_title', 'peach_widget_title');
function peach_widget_title($t) {
    return null;
}

// Enable shortcodes in text widgets
add_filter('widget_text', 'do_shortcode');

// Fixed widgets html braking
add_filter('widget_display_callback', 'clean_widget_display_callback', 10, 3);
function clean_widget_display_callback($instance, $widget, $args) {
    $instance['filter'] = false;
    return $instance;
}

// Adds link to homepage for header image
add_filter('get_header_image_tag', 'add_header_image_link', 10, 3);
function add_header_image_link($html, $header, $attr) {
    return '<a href="' . site_url() . '" title="' . get_bloginfo('name') . '">' . $html . '</a>';
}

// Prevent WP to mess HTML in post/pages content
remove_filter ('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');

// Bread crumb add Home URL at the beginning
add_filter('menu_breadcrumb_markup', 'add_home_menu_breadcrumb_markup', 9, 1);
function add_home_menu_breadcrumb_markup($markup) {
    $separator = ' / ';
    $markupHome = '<a href="' . esc_url(get_home_url()) . '">';
    $markupHome .= esc_html(__('Home'));
    $markupHome .= '</a>';
    $markupHome = (string) apply_filters('menu_breadcrumb_item_markup', $markupHome, null);
    $markupHome .= '<span class="sep">' . $separator . '</span>';
    return $markupHome . $markup;
}

// Add Base url tu custom relative links
add_filter('wp_nav_menu_objects', 'add_base_url_to_relative_menu_links', 10, 2);
function add_base_url_to_relative_menu_links($sorted_menu_items, $args) {
    foreach ($sorted_menu_items as $key => $val) {
        if (isset($val->url) && (strstr($val->url, '://') === false)) {
            $sorted_menu_items[$key]->url = site_url() . $val->url;
        }
    }
    return $sorted_menu_items;
}

//Remove visual tab
add_filter( 'user_can_richedit' , '__return_false', 50 );

// Removing emojis
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_filter('the_content', 'convert_smilies');

//Add mobile and desktop class to body

add_filter( 'body_class','mobile_desktop_body_class' );
function mobile_desktop_body_class( $classes ) {
	if(wp_is_mobile()){
		$classes[]= 'mobile';
	}else{
		$classes[]= 'desktop';
	}
	return $classes;
}


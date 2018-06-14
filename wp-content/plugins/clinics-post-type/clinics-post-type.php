<?php

/*
Plugin Name: Clinic Page Type
Description: Creates a new page type for clinic locations
Version: 0.0.1
Author: CyberSolutionsLLC
License: GPLv2 or later
*/

// abort if this file is called directly
if (! defined('WPINC')) {
    die;
}

add_action('init', 'clinic_register');

function clinic_register() {

    register_post_type('clinic_page', array(
        'labels' => array(
            'name' => _x('Clinics', 'post type general name'),
            'singular_name' => _x('Clinic Item', 'post type singular name'),
            'add_new' => _x('Add New', 'Clinic Item'),
            'add_new_item' => __('Add New Clinic Item'),
            'all_items' => __('All Clinics'),
            'edit_item' => __('Edit Clinic Item'),
            'new_item' => __('New Clinic Item'),
            'view_item' => __('View Clinic Item'),
            'search_items' => __('Search Clinics'),
            'not_found' =>  __('Nothing found'),
            'not_found_in_trash' => __('Nothing found in Trash'),
            'parent_item_colon' => '|'
        ),
        'public' => true,
        'capability_type' => 'page',
        'map_meta_cap' => true,
        'menu_position' => 20,
        'hierarchical' => true,
        'publicly_queryable' => true,
        '_builtin' => false,
        'show_ui' => true,
        'has_archive' => false,
        'query_var' => true,
        'rewrite' => array("slug" => "location", "with_front" => false),
        'delete_with_user' => false,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'page-attributes', 'comments', 'revisions'),
        'show_in_rest' => true,
        'rest_base' => 'clinic_page',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    ));


    add_filter('body_class', 'add_clinic_body_class');

    function add_clinic_body_class($classes) {
        global $post;
        if (get_post_type($post) != 'clinic_page') {
            return $classes;
        }
        $additional_classes = array('page-template-page-templates', 'page');
        $classes = array_merge($classes, $additional_classes);
        return $classes;
    }


    register_post_type('clinic_booking_page', array(
        'labels' => array(
            'name' => _x('Clinic Bookings', 'post type general name'),
            'singular_name' => _x('Clinic Booking Item', 'post type singular name'),
            'add_new' => _x('Add New', 'Clinic Booking item'),
            'add_new_item' => __('Add New Clinic Booking Item'),
            'all_items' => __('All Clinic Bookings'),
            'edit_item' => __('Edit Clinic Booking Item'),
            'new_item' => __('New Clinic Booking Item'),
            'view_item' => __('View Clinic Booking Item'),
            'search_items' => __('Search Clinic Bookings'),
            'not_found' =>  __('Nothing found'),
            'not_found_in_trash' => __('Nothing found in Trash'),
            'parent_item_colon' => '|'
        ),
        'public' => true,
        'capability_type' => 'page',
        'map_meta_cap' => true,
        'menu_position' => 20,
        'hierarchical' => true,
        'publicly_queryable' => true,
        '_builtin' => false,
        'show_ui' => true,
        'has_archive' => false,
        'query_var' => true,
        'rewrite' => array("slug" => "book", "with_front" => false),
        'delete_with_user' => false,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'page-attributes', 'comments', 'revisions'),
        'show_in_rest' => true,
        'rest_base' => 'clinic_booking_page',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    ));


    add_filter('body_class', 'add_clinic_booking_body_class');

    function add_clinic_booking_body_class($classes) {
        global $post;
        if (get_post_type($post) != 'clinic_booking_page') {
            return $classes;
        }
        $additional_classes = array('page-template-page-templates', 'page');
        $classes = array_merge($classes, $additional_classes);
        return $classes;
    }


    add_action("admin_init", "admin_init");

    function admin_init() {
        add_meta_box("clinic_location-meta", "Clinic Location Data", "clinic_location_meta", "clinic_page", "normal", "high");
        add_meta_box("clinic_location_time_days-meta", "Clinic Business Hours", "clinic_location_business_hours", "clinic_page", "side", "low");
        add_meta_box("clinic_booking-meta", "Clinic Booking Data", "clinic_book_meta", "clinic_booking_page", "normal", "high");
        add_meta_box("clinic_booking-pdf-registration", "Clinic Booking PDF Registration Form", "pdf_registration_link", "clinic_booking_page", "side", "low");
        add_meta_box("clinic_booking-pdf-consent", "Clinic Booking PDF Minor Consent Form", "pdf_consent_link", "clinic_booking_page", "side", "low");
    }

    function pdf_registration_link() {
        global $post;
        $custom  = get_post_custom($post->ID);
        $link    = isset($custom["pdf_registration_id"][0]) ? $custom["pdf_registration_id"][0] : null;
        $count   = 0;
        echo '<div class="link_header">';
        $query_pdf_args = array(
            'post_type' => 'attachment',
            'post_mime_type' =>'application/pdf',
            'post_status' => 'inherit',
            'posts_per_page' => -1,
        );
        $query_pdf = new WP_Query($query_pdf_args);

        echo '<select name="pdf_registration_id">';
        echo '<option class="pdf_select">Select PDF Registration Form</option>';
        foreach ($query_pdf->posts as $file) {
            if ($link == $file->ID) {
                echo '<option value="'.$file->ID.'" selected="true">'.$file->post_title.'</option>';
            } else {
                echo '<option value="'.$file->ID.'">'.$file->post_title.'</option>';
            }
            $count++;
        }
        echo '</select><br /></div>';
        echo '<p>Selecting a pdf file from the above list to attach to this post.</p>';
        echo '<div class="pdf_count"><span>Files:</span> <b>'.$count.'</b></div>';
    }

    function pdf_consent_link() {
        global $post;
        $custom  = get_post_custom($post->ID);
        $link    = isset($custom["pdf_consent_id"][0]) ? $custom["pdf_consent_id"][0] : null;
        $count   = 0;
        echo '<div class="link_header">';
        $query_pdf_args = array(
            'post_type' => 'attachment',
            'post_mime_type' =>'application/pdf',
            'post_status' => 'inherit',
            'posts_per_page' => -1,
        );
        $query_pdf = new WP_Query($query_pdf_args);

        echo '<select name="pdf_consent_id">';
        echo '<option class="pdf_select">Select PDF Registration Form</option>';
        foreach ($query_pdf->posts as $file) {
            if ($link == $file->ID) {
                echo '<option value="'.$file->ID.'" selected="true">'.$file->post_title.'</option>';
            } else {
                echo '<option value="'.$file->ID.'">'.$file->post_title.'</option>';
            }
            $count++;
        }
        echo '</select><br /></div>';
        echo '<p>Selecting a pdf file from the above list to attach to this post.</p>';
        echo '<div class="pdf_count"><span>Files:</span> <b>'.$count.'</b></div>';
    }

    function clinic_location_meta() {
        global $post;
        $custom = get_post_custom($post->ID);
        ?>
        <p><label for="clinic_location_clinic_name"><?php echo __('Clinic Name') ?>:</label><br />
            <input id="clinic_location_clinic_name" type="text" name="clinic_name" value="<?php echo isset($custom["clinic_name"][0]) ? $custom["clinic_name"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_clinic_api_id"><?php echo __('Clinic API ID') ?>:</label><br />
            <input id="clinic_location_clinic_api_id" type="text" name="api_id" value="<?php echo isset($custom["api_id"][0]) ? $custom["api_id"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_address"><?php echo __('Clinic Address') ?>:</label><br />
            <input id="clinic_location_address" type="text" name="address" value="<?php echo isset($custom["address"][0]) ? $custom["address"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_city"><?php echo __('Clinic City') ?>:</label><br />
            <input id="clinic_location_city" type="text" name="city" value="<?php echo isset($custom["city"][0]) ? $custom["city"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_state"><?php echo __('Clinic State') ?>:</label><br />
            <input id="clinic_location_state" type="text" name="state" value="<?php echo isset($custom["state"][0]) ? $custom["state"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_zip_code"><?php echo __('Clinic Zip') ?>:</label><br />
            <input id="clinic_location_zip_code" type="text" name="zip_code" value="<?php echo isset($custom["zip_code"][0]) ? $custom["zip_code"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_phone"><?php echo __('Clinic Phone') ?>:</label><br />
            <input id="clinic_location_phone" type="text" name="phone" value="<?php echo isset($custom["phone"][0]) ? $custom["phone"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_fax"><?php echo __('Clinic Fax') ?>:</label><br />
            <input id="clinic_location_fax" type="text" name="fax" value="<?php echo isset($custom["fax"][0]) ? $custom["fax"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_email"><?php echo __('Clinic Email') ?>:</label><br />
            <input id="clinic_location_email" type="text" name="email" value="<?php echo isset($custom["email"][0]) ? $custom["email"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_services"><?php echo __('Clinic Services') ?>:</label><br />
            <textarea id="clinic_location_services" cols="50" rows="5" name="services"><?php echo isset($custom["services"][0]) ? $custom["services"][0] : ''; ?></textarea>
        </p>
        <p><label for="clinic_location_latitude"><?php echo __('Clinic Latitude') ?>:</label><br />
            <input id="clinic_location_latitude" type="text" name="latitude" value="<?php echo isset($custom["latitude"][0]) ? $custom["latitude"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_longitude"><?php echo __('Clinic Longitude') ?>:</label><br />
            <input id="clinic_location_longitude" type="text" name="longitude" value="<?php echo isset($custom["longitude"][0]) ? $custom["longitude"][0] : ''; ?>"/>
        </p>
        <?php
    }

    function clinic_location_business_hours() {
        global $post;
        $custom = get_post_custom($post->ID);
        ?>
        <p><label for="clinic_location_clinic_monday"><?php echo __('Clinic Monday Business Hours') ?>:</label><br />
            <input id="clinic_location_clinic_monday" type="text" name="clinic_monday" value="<?php echo isset($custom["clinic_monday"][0]) ? $custom["clinic_monday"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_clinic_tuesday"><?php echo __('Clinic Tuesday Business Hours') ?>:</label><br />
            <input id="clinic_location_clinic_tuesday" type="text" name="clinic_tuesday" value="<?php echo isset($custom["clinic_tuesday"][0]) ? $custom["clinic_tuesday"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_clinic_wednesday"><?php echo __('Clinic Wednesday Business Hours') ?>:</label><br />
            <input id="clinic_location_clinic_wednesday" type="text" name="clinic_wednesday" value="<?php echo isset($custom["clinic_wednesday"][0]) ? $custom["clinic_wednesday"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_clinic_thursday"><?php echo __('Clinic Thursday Business Hours') ?>:</label><br />
            <input id="clinic_location_clinic_thursday" type="text" name="clinic_thursday" value="<?php echo isset($custom["clinic_thursday"][0]) ? $custom["clinic_thursday"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_clinic_friday"><?php echo __('Clinic Friday Business Hours') ?>:</label><br />
            <input id="clinic_location_clinic_friday" type="text" name="clinic_friday" value="<?php echo isset($custom["clinic_friday"][0]) ? $custom["clinic_friday"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_clinic_saturday"><?php echo __('Clinic Saturday Business Hours') ?>:</label><br />
            <input id="clinic_location_clinic_saturday" type="text" name="clinic_saturday" value="<?php echo isset($custom["clinic_saturday"][0]) ? $custom["clinic_saturday"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_clinic_sunday"><?php echo __('Clinic Sunday Business Hours') ?>:</label><br />
            <input id="clinic_location_clinic_sunday" type="text" name="clinic_sunday" value="<?php echo isset($custom["clinic_sunday"][0]) ? $custom["clinic_sunday"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_clinic_open_days"><?php echo __('Clinic Open Days a Year') ?>:</label><br />
            <input id="clinic_location_clinic_open_days" type="text" name="clinic_open_days" value="<?php echo isset($custom["clinic_open_days"][0]) ? $custom["clinic_open_days"][0] : ''; ?>"/>
        </p>
        <p><label for="clinic_location_clinic_close_days"><?php echo __('Clinic Close Days a Year') ?>:</label><br />
            <input id="clinic_location_clinic_close_days" type="text" name="clinic_close_days" value="<?php echo isset($custom["clinic_close_days"][0]) ? $custom["clinic_close_days"][0] : ''; ?>"/>
        </p>
        <?php
    }

    function clinic_book_meta() {
        global $post;
        $custom = get_post_custom($post->ID);
        ?>
        <p><label for="clinic_location_clinic_book_api_id"><?php echo __('Clinic API ID') ?>:</label><br />
            <input id="clinic_location_clinic_book_api_id" type="text" name="book_api_id" value="<?php echo isset($custom["book_api_id"][0]) ? $custom["book_api_id"][0] : ''; ?>"/>
        </p>
        <?php
    }


    add_action('save_post', 'save_details');

    function save_details(){
        global $post;
        if (get_post_type($post) == 'clinic_page') {
            if (isset($_POST["clinic_name"])) {
                update_post_meta($post->ID, "clinic_name", $_POST["clinic_name"]);
            }
            if (isset($_POST["api_id"])) {
                update_post_meta($post->ID, "api_id", $_POST["api_id"]);
            }
            if (isset($_POST["address"])) {
                update_post_meta($post->ID, "address", $_POST["address"]);
            }
            if (isset($_POST["city"])) {
                update_post_meta($post->ID, "city", $_POST["city"]);
            }
            if (isset($_POST["state"])) {
                update_post_meta($post->ID, "state", $_POST["state"]);
            }
            if (isset($_POST["zip_code"])) {
                update_post_meta($post->ID, "zip_code", $_POST["zip_code"]);
            }
            if (isset($_POST["phone"])) {
                update_post_meta($post->ID, "phone", $_POST["phone"]);
            }
            if (isset($_POST["fax"])) {
                update_post_meta($post->ID, "fax", $_POST["fax"]);
            }
            if (isset($_POST["email"])) {
                update_post_meta($post->ID, "email", $_POST["email"]);
            }
            if (isset($_POST["services"])) {
                update_post_meta($post->ID, "services", $_POST["services"]);
            }
            if (isset($_POST["latitude"])) {
                update_post_meta($post->ID, "latitude", $_POST["latitude"]);
            }
            if (isset($_POST["longitude"])) {
                update_post_meta($post->ID, "longitude", $_POST["longitude"]);
            }

            if (isset($_POST["clinic_monday"])) {
                update_post_meta($post->ID, "clinic_monday", $_POST["clinic_monday"]);
            }
            if (isset($_POST["clinic_tuesday"])) {
                update_post_meta($post->ID, "clinic_tuesday", $_POST["clinic_tuesday"]);
            }
            if (isset($_POST["clinic_wednesday"])) {
                update_post_meta($post->ID, "clinic_wednesday", $_POST["clinic_wednesday"]);
            }
            if (isset($_POST["clinic_thursday"])) {
                update_post_meta($post->ID, "clinic_thursday", $_POST["clinic_thursday"]);
            }
            if (isset($_POST["clinic_friday"])) {
                update_post_meta($post->ID, "clinic_friday", $_POST["clinic_friday"]);
            }
            if (isset($_POST["clinic_saturday"])) {
                update_post_meta($post->ID, "clinic_saturday", $_POST["clinic_saturday"]);
            }
            if (isset($_POST["clinic_sunday"])) {
                update_post_meta($post->ID, "clinic_sunday", $_POST["clinic_sunday"]);
            }
            if (isset($_POST["clinic_open_days"])) {
                update_post_meta($post->ID, "clinic_open_days", $_POST["clinic_open_days"]);
            }
            if (isset($_POST["clinic_close_days"])) {
                update_post_meta($post->ID, "clinic_close_days", $_POST["clinic_close_days"]);
            }
        } else if (get_post_type($post) == 'clinic_booking_page') {
            if (isset($_POST["book_api_id"])) {
                update_post_meta($post->ID, "book_api_id", $_POST["book_api_id"]);
            }
            if (isset($_POST["pdf_registration_id"])) {
                update_post_meta($post->ID, "pdf_registration_id", $_POST["pdf_registration_id"]);
            }
            if (isset($_POST["pdf_consent_id"])) {
                update_post_meta($post->ID, "pdf_consent_id", $_POST["pdf_consent_id"]);
            }
        }
    }


    add_filter('template_include', 'clinic_page_template', 99);

    function clinic_page_template($template) {
        $file_name = 'single-clinic_page.php';
        $booking_file_name = 'single-clinic-book_page.php';

        if (get_post_type() == 'clinic_page') {
            if (is_single()) {
                if (locate_template($file_name)) {
                    $template = locate_template($file_name);
                } else {
                    // Template not found in theme's folder, use plugin's template as a fallback
                    $template = dirname(__FILE__) . '/templates/' . $file_name;
                }
            }
        } else if (get_post_type() == 'clinic_booking_page') {
            if (is_single()) {
                if (locate_template($booking_file_name)) {
                    $template = locate_template($booking_file_name);
                } else {
                    // Template not found in theme's folder, use plugin's template as a fallback
                    $template = dirname(__FILE__) . '/templates/' . $booking_file_name;
                }
            }
        }
        return $template;
    }


    add_action('admin_menu', 'api_config_add_admin_menu');
    add_action('admin_init', 'api_config_settings_init');

    function api_config_add_admin_menu() {
        add_submenu_page('tools.php', 'Clinic Configuration', 'Clinic Configuration', 'manage_options', 'clinics-post-type', 'api_config_options_page');
    }

    function api_config_settings_init() {
        register_setting('pluginPage', 'api_config_settings');
        add_settings_section(
            'api_config_pluginPage_section',
            __('Clinic APIs Configuration', 'wordpress'),
            'api_config_settings_section_callback',
            'pluginPage'
        );
        add_settings_field(
            'api_config_google_maps_api_key',
            __('Google Maps API Key', 'wordpress'),
            'api_config_google_maps_api_key_render',
            'pluginPage',
            'api_config_pluginPage_section'
        );
        add_settings_field(
            'api_config_google_maps_secret_key',
            __('Google Maps Secret Key', 'wordpress'),
            'api_config_google_maps_secret_key_render',
            'pluginPage',
            'api_config_pluginPage_section'
        );
        add_settings_field(
            'api_config_clockwise_md_group_code',
            __('Clockwise.MD Group Code', 'wordpress'),
            'api_config_clockwise_md_group_code_render',
            'pluginPage',
            'api_config_pluginPage_section'
        );
        add_settings_field(
            'api_config_clockwise_md_auth_token',
            __('Clockwise.MD Auth Token', 'wordpress'),
            'api_config_clockwise_md_auth_token_render',
            'pluginPage',
            'api_config_pluginPage_section'
        );
    }

    function api_config_google_maps_api_key_render() {
        $options = get_option('api_config_settings');
        ?>
        <input type='text' name='api_config_settings[api_config_google_maps_api_key]' value='<?php echo $options['api_config_google_maps_api_key']; ?>'>
        <?php

    }

    function api_config_google_maps_secret_key_render() {
        $options = get_option('api_config_settings');
        ?>
        <input type='text' name='api_config_settings[api_config_google_maps_secret_key]' value='<?php echo $options['api_config_google_maps_secret_key']; ?>'>
        <?php
    }

    function api_config_clockwise_md_group_code_render() {
        $options = get_option('api_config_settings');
        ?>
        <input type='text' name='api_config_settings[api_config_clockwise_md_group_code]' value='<?php echo $options['api_config_clockwise_md_group_code']; ?>'>
        <?php
    }

    function api_config_clockwise_md_auth_token_render() {
        $options = get_option('api_config_settings');
        ?>
        <input type='text' name='api_config_settings[api_config_clockwise_md_auth_token]' value='<?php echo $options['api_config_clockwise_md_auth_token']; ?>'>
        <?php
    }

    function api_config_settings_section_callback() {
        echo __('APIs configuration used on clinic pages', 'wordpress');
    }

    function api_config_options_page() {
        ?>
        <form action='options.php' method='post'>
            <?php
            settings_fields('pluginPage');
            do_settings_sections('pluginPage');
            submit_button();
            ?>
        </form>
        <?php
    }


    add_filter('template_include', 'load_api_script_for_template', 1000);

    function load_api_script_for_template($template) {
        if (is_page_template('page-templates/template-page-clinics.php')
            || (get_post_type() == 'clinic_page')
            || (get_post_type() == 'clinic_booking_page')
            || is_front_page()
        ) {
            $setting = get_option('api_config_settings', array());
            $googleApiKey = '';
            $clockwiseMdGroupCode = '0';
            if (isset($setting['api_config_google_maps_api_key'])) {
                $googleApiKey = $setting['api_config_google_maps_api_key'];
            }
            if (isset($setting['api_config_clockwise_md_group_code'])) {
                $clockwiseMdGroupCode = $setting['api_config_clockwise_md_group_code'];
            }
            wp_enqueue_script('clockwisepublic_mustache_script', '//s3-us-west-1.amazonaws.com/clockwisepublic/mustache.js', array('jquery'), 1.0, false);
            wp_enqueue_script('pubnub_script', '//cdn.pubnub.com/pubnub.min.js', array('clockwisepublic_mustache_script'), 1.0, false);
            wp_enqueue_script('google_maps_script', '//maps.googleapis.com/maps/api/js?v=3&amp;key=' . $googleApiKey, array('pubnub_script'), 1.0, false);
            wp_enqueue_script('clockwisepublic_geoposition_script', '//s3-us-west-1.amazonaws.com/clockwisepublic/geoposition.js', array('google_maps_script'), 1.0, false);
            wp_enqueue_script('clockwisepublic_infobox_script', '//s3-us-west-1.amazonaws.com/clockwisepublic/infobox.js', array('clockwisepublic_geoposition_script'), 1.0, false);

            if (get_post_type() == 'clinic_booking_page') {
                wp_enqueue_script('swal_script', '//cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.min.js', array('jquery'), 1.0, false);
                wp_enqueue_script('jquery_ui_script', '//code.jquery.com/ui/1.10.3/jquery-ui.min.js', array('jquery'), 1.0, false);
                wp_enqueue_style('swal_style', '//cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.min.css');
                wp_enqueue_style('jquery_ui_style', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
                wp_enqueue_style('hint_base_style', '//cdnjs.cloudflare.com/ajax/libs/hint.css/2.5.0/hint.base.min.css');
                wp_enqueue_style('hint_style', '//cdnjs.cloudflare.com/ajax/libs/hint.css/2.5.0/hint.css');
            }

            if (is_page_template('page-templates/template-page-clinics.php') || is_front_page()) {
                wp_enqueue_script('clockwisepublic_available_times_script', 'https://www.clockwisemd.com/hospitals/clockwise_api.js', array('clockwisepublic_geoposition_script'), 1.0, true);
                wp_enqueue_script('clockwisepublic_data_script', 'https://www.clockwisemd.com/groups/' . $clockwiseMdGroupCode . '.js', array('clockwisepublic_available_times_script'), 1.0, true);
                wp_enqueue_script('clockwisepublic_data_script_rewrites', plugins_url('/js/clockwisemd_data_rewrite.js', __FILE__), array('clockwisepublic_data_script'), 1.0, true);

                if (is_page_template('page-templates/template-page-clinics.php')) {
                    $isCurrentLocation = false;
                    $locationQuery = null;
                    if (($isCurrentLocation = (isset($_GET['location_search_current']) && ($_GET['location_search_current'] == '1')))
                        || ($locationQuery = (isset($_GET['location_search_query']) && (trim($_GET['location_search_query']) != '')) ? $_GET['location_search_query'] : null)
                    ) {
                        function add_this_script_footer($isCurrentLocation, $locationQuery)
                        { ?>
                            <script type="text/javascript">
                                //<![CDATA[
                                <?php if ($isCurrentLocation): ?>
                                jQuery(document).ready(function () {
                                    function clickOnUseMyLocation() {
                                        if (typeof(btnMyLoc) != 'undefined') {
                                            jQuery('#current-location-btn').click();
                                        } else {
                                            setTimeout(function () {
                                                clickOnUseMyLocation();
                                            }, 300);
                                        }
                                    }

                                    clickOnUseMyLocation();
                                });
                                <?php elseif ($locationQuery): ?>
                                jQuery(document).ready(function () {
                                    function clickOnSearchLocation() {
                                        if (typeof(geocoder) != 'undefined') {
                                            jQuery('#address').val('<?php echo trim($locationQuery); ?>');
                                            jQuery('#address-search-btn').click();
                                        } else {
                                            setTimeout(function () {
                                                clickOnSearchLocation();
                                            }, 300);
                                        }
                                    }

                                    clickOnSearchLocation();
                                });
                                <?php endif; ?>
                                //]]>
                            </script>
                        <?php }

                        add_action(
                            'wp_footer',
                            function () use ($isCurrentLocation, $locationQuery) {
                                add_this_script_footer($isCurrentLocation, $locationQuery);
                            }
                        );
                    }
                }
            } else {
                wp_enqueue_script('clockwisepublic_available_times_script', 'https://www.clockwisemd.com/hospitals/clockwise_api.js', array('clockwisepublic_geoposition_script'), 1.0, true);
            }
            wp_enqueue_style('clockwise_map_style', '//s3-us-west-1.amazonaws.com/clockwisepublic/clockwise_map.css');
        }
        return $template;
    }


    add_action('parse_request', 'get_clinic_page_url_handler');

    function get_clinic_page_url_handler() {
        $request_path = $_SERVER["HTTP_HOST"] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $base_parts = parse_url(site_url());
        $base_path =  $base_parts['host'] . (isset($base_parts['path']) ? $base_parts['path'] : '');

        if ($request_path == $base_path . '/get-clinic-book-page-by-api-id') {
            $postApiId = isset($_GET['id']) ? $_GET['id'] : null;
            $getTimeValue = isset($_GET['time']) ? $_GET['time'] : null;
            $posts = get_posts(array(
                'numberposts' => 1,
                'post_type' => 'clinic_booking_page',
                'meta_key' => 'book_api_id',
                'meta_value' => $postApiId,
            ));
            if (count($posts)) {
                $post = $posts[0];
                $url = get_permalink($post);
                if ($getTimeValue) {
                    $url = add_query_arg(array(
                        'time' => urlencode($getTimeValue),
                    ), $url);
                }
            } else {
                $url = site_url();
            }
            wp_redirect($url);
            exit;
        } else if ($request_path == $base_path . '/get-clinic-page-by-api-id') {
            $postApiId = isset($_GET['id']) ? $_GET['id'] : null;
            $posts = get_posts(array(
                'numberposts' => 1,
                'post_type'	=> 'clinic_page',
                'meta_key' => 'api_id',
                'meta_value' => $postApiId,
            ));
            if (count($posts)) {
                $post = $posts[0];
                $url = get_permalink($post);
            } else {
                $url = site_url();
            }
            wp_redirect($url);
            exit;
        } else if (($request_path == $base_path . '/get-clinic-booking-reason-ajax') && (! empty($_POST))) {
            $setting = get_option('api_config_settings', array());
            $authToken = '';
            if (isset($setting['api_config_clockwise_md_auth_token'])) {
                $authToken = $setting['api_config_clockwise_md_auth_token'];
            }
            $apiId = isset($_POST['clinic_id']) ? urlencode($_POST['clinic_id']) : '';
            $url = "https://api.clockwisemd.com/v1/reasons?hospital_id={$apiId}";
            $args = array(
                'headers' => array(
                    'Authtoken' => $authToken,
                    'Accept' => 'application/json'
                )
            );
            $response = wp_remote_get($url, $args);
            wp_send_json($response['body']);
            exit;
        } else if (($request_path == $base_path . '/get-clinic-booking-times-ajax') && (! empty($_POST))) {
            $setting = get_option('api_config_settings', array());
            $authToken = '';
            if (isset($setting['api_config_clockwise_md_auth_token'])) {
                $authToken = $setting['api_config_clockwise_md_auth_token'];
            }
            $apiId = isset($_POST['clinic_id']) ? urlencode($_POST['clinic_id']) : '';
            $queueId = isset($_POST['queue_id']) ? urlencode($_POST['queue_id']) : '';
            $reasonId = isset($_POST['reason']) ? urlencode($_POST['reason']) : '';
            $url = "https://api.clockwisemd.com/v1/schedule_providers/times?hospital_id={$apiId}&appointment_queue_id={$queueId}&reason_description={$reasonId}";
            $args = array(
                'headers' => array(
                    'Authtoken' => $authToken,
                    'Accept' => 'application/json'
                )
            );
            $response = wp_remote_get($url, $args);
            wp_send_json($response['body']);
            exit;
        } else if (($request_path == $base_path . '/post-clinic-booking-submit-ajax') && (! empty($_POST))) {
            $setting = get_option('api_config_settings', array());
            $authToken = '';
            if (isset($setting['api_config_clockwise_md_auth_token'])) {
                $authToken = $setting['api_config_clockwise_md_auth_token'];
            }
            $url = "https://api.clockwisemd.com/v1/appointments/create";
            $args = array(
                'method' => 'POST',
                'body' => array(
                    'hospital_id' => isset($_POST['clinic_id']) ? $_POST['clinic_id'] : '',
                    'first_name' => isset($_POST['first_name']) ? $_POST['first_name'] : '',
                    'last_name' => isset($_POST['last_name']) ? $_POST['last_name'] : '',
                    'apt_time' => isset($_POST['date_time']) ? $_POST['date_time'] : '',
                    'phone_number' => isset($_POST['phone']) ? $_POST['phone'] : '',
                    'email' => isset($_POST['email']) ? $_POST['email'] : '',
                    'dob' => isset($_POST['dob']) ? $_POST['dob'] : '',
                    'reason_id' => isset($_POST['reason_id']) ? $_POST['reason_id'] : '',
                //    'reason_description' => isset($_POST['reason_val']) ? $_POST['reason_val'] : '',
                    'type' => 'online',
                    'is_staff_added' => false,
                    'is_urgentcare' => true,
                    'is_new_patient' => isset($_POST['patient_type']) ? $_POST['patient_type'] : '',
                    'can_send_alert_sms' => true,
                    'can_send_sms_survey' => true,
                    'pager_minutes' => isset($_POST['pager_minutes']) ? $_POST['pager_minutes'] : '',
                ),
                'headers' => array(
                    'Authtoken' => $authToken,
                    'Accept' => 'application/json'
                )
            );
            $response = wp_remote_post($url, $args);
            wp_send_json($response['body']);
            exit;
        }
    }


    add_filter('get_default_comment_status', 'wp_docs_open_comments_for_clinic_page', 10, 3);

    function wp_docs_open_comments_for_clinic_page($status, $post_type, $comment_type) {
        if (('clinic_page' !== $post_type) && ('clinic_booking_page' !== $post_type)) {
            return $status;
        }
        return 'closed';
    }


    add_shortcode('locations_search_box', 'get_locations_search_box');

    function get_locations_search_box() {
        $posts = get_posts(array(
            'public' => true,
            'post_status' => 'publish',
            'numberposts' => 1,
            'post_type' => 'page',
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-templates/template-page-clinics.php'
        ));
        if (count($posts)) {
            $post = $posts[0];
            $url = get_permalink($post);

            $html = <<<locationForm
            <div class="location-search-container">
                <form method="get" action="{$url}" class="location-search-form">
                    <label for="location_search_query">Find a Location</label>
                    <input id="location_search_query" type="text" placeholder="Enter City/State or ZIP" name="location_search_query"/>
                    <button type="submit" onclick="return (jQuery('#location_search_query').val().trim() != '');">Go</button>
                </form>
                <p class="location-search-delimiter">-Or-</p>
                <form method="get" action="{$url}" class="current-location-search-form">
                    <input type="hidden" name="location_search_current" value="1"/>
                    <button type="submit">Use Current Location</button>
                </form>
            </div>
locationForm;

            return $html;
        } else {
            return '';
        }
    }


}

function get_pdf_consent_file_link() {
    global $wp_query;
    $custom = get_post_custom($wp_query->post->ID);
    $pdfId = isset($custom["pdf_consent_id"][0]) ? $custom["pdf_consent_id"][0] : '';
    if ($pdfId) {
        $url = wp_get_attachment_url($pdfId);
        $url = ($url !== false) ? $url : '';
    } else {
        $url = '';
    }
    echo $url;
}

function get_pdf_registration_file_link() {
    global $wp_query;
    $custom = get_post_custom($wp_query->post->ID);
    $pdfId = isset($custom["pdf_registration_id"][0]) ? $custom["pdf_registration_id"][0] : '';
    if ($pdfId) {
        $url = wp_get_attachment_url($pdfId);
        $url = ($url !== false) ? $url : '';
    } else {
        $url = '';
    }
    echo $url;
}

function my_rewrite_flush() {
    // First, we "add" the custom post type via the above written function.
    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
    // They are only referenced in the post_type column with a post entry,
    // when you add a post of this CPT.
    clinic_register();

    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'my_rewrite_flush');
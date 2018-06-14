<?php

/*
 * Template Name: Clinic Page Template
 * Description: A Page Template for Clinic
 */


get_header(); ?>

    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <header class="entry-header">
                    <div class="header-title">
                        <?php if(wp_is_mobile()):?>
                            <div class="breadcrumb m-breadcrumb">
                                <?php
                                if (function_exists('menu_breadcrumb')) {
                                    menu_breadcrumb(
                                        'top',                             // Menu Location to use for breadcrumb
                                        ' / ',                        // separator between each breadcrumb
                                        '<p class="menu-breadcrumb">',      // output before the breadcrumb
                                        '</p>'                              // output after the breadcrumb
                                    );
                                }
                                ?>
                            </div>
                        <?php endif;?>
                        <h1 class="entry-title"><?php echo __('Locations'); ?></h1>
                        <?php twentyseventeen_edit_link( get_the_ID() ); ?>
                        <?php if(!wp_is_mobile()):?>
                            <div class="breadcrumb">
                                <?php
                                if (function_exists('menu_breadcrumb')) {
                                    menu_breadcrumb(
                                        'top',                             // Menu Location to use for breadcrumb
                                        ' / ',                        // separator between each breadcrumb
                                        '<p class="menu-breadcrumb">',      // output before the breadcrumb
                                        '</p>'                              // output after the breadcrumb
                                    );
                                }
                                ?>
                            </div>
                        <?php endif;?>
                    </div>
                </header><!-- .entry-header -->
                <div class="content">
                    <?php if(!wp_is_mobile()):?>
                        <div class="content-one-column">
                        <?php
                        while ( have_posts() ) : the_post();
                            $post = get_post();
                            $custom = get_post_custom($post->ID);
                            $addressString = stripslashes(isset($custom["address"][0]) ? $custom["address"][0] : '').', '
                                .stripslashes(isset($custom["city"][0]) ? $custom["city"][0] : '').', '
                                .stripslashes(isset($custom["state"][0]) ? $custom["state"][0] : '').'  '
                                .stripslashes(isset($custom["zip_code"][0]) ? $custom["zip_code"][0] : '');
                        ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <div class="entry-content">
                                <div class="loc-left-panel top_area">
                                    <h4>
                                        <?php echo stripslashes(isset($custom["clinic_name"][0]) ? $custom["clinic_name"][0] : ''); ?>  <span>Center</span>
                                    </h4>
                                    <div class="border"><p></p></div>
                                </div>
                                <div class="loc-right-panel top_area">
                                    <h4>Location</h4>
                                    <div class="border"><p></p></div>
                                </div>
                                <div class="loc-left-panel address_email">
                                    <ul class="address" id="detail">
                                        <li>
                                            <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/address.png"><p><span>Address:</span> <?php
                                                echo stripslashes(isset($custom["address"][0]) ? $custom["address"][0] : '')
                                                    .'<br/>'.stripslashes(isset($custom["city"][0]) ? $custom["city"][0] : '')
                                                    .', '.stripslashes(isset($custom["state"][0]) ? $custom["state"][0] : '')
                                                    .'  '.stripslashes(isset($custom["zip_code"][0]) ? $custom["zip_code"][0] : ''); ?>
                                            </p>
                                        </li>
                                        <li>
                                            <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/fone.png"><p><span style="width:auto">Phone:</span>&nbsp; <?php echo isset($custom["phone"][0]) ? $custom["phone"][0] : ''; ?></p>
                                        </li>
                                        <li>
                                            <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/fax.png"><p><span style="width:auto">Fax:</span>&nbsp;<?php echo isset($custom["fax"][0]) ? $custom["fax"][0] : ''; ?></p>
                                        </li>
                                        <li>
                                            <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/email.png"><p><span>Email:</span> <?php echo isset($custom["email"][0]) ? $custom["email"][0] : ''; ?></p>
                                        </li>
                                        <li>
                                            <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/holidays.png"><p><span>Holidays:</span><samp> We are open <?php echo isset($custom["clinic_open_days"][0]) ? $custom["clinic_open_days"][0] : ''; ?> days per year!</samp> Closed: <?php echo isset($custom["clinic_close_days"][0]) ? $custom["clinic_close_days"][0] : ''; ?>.</p>
                                        </li>
                                        <?php if((isset($custom["services"][0]) ? $custom["services"][0] : '') != '') {?>
                                            <li style="border-bottom:0">
                                                <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/icon-4.png" style="height: 27px;"><p><span style="width:auto">Services Offered:</span><br/> <?php echo nl2br(isset($custom["services"][0]) ? $custom["services"][0] : ''); ?></p>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="loc-right-panel address_email">
                                    <div class="waing_time">
                                        <div class="get-inline">
                                            <h2 class="get-inline-header">
                                                <?php echo __('Check In Online'); ?>
                                            </h2>
                                            <a target="_blank" href="<?php echo site_url()."/get-clinic-book-page-by-api-id?id=".(isset($custom["api_id"][0]) ? $custom["api_id"][0] : ''); ?>">
                                                <button><?php echo __('Check In'); ?></button>
                                            </a>
                                        </div>
                                        <div class="addresses">
                                            <div class="row-fluid_maps">
                                                <div class="timings-loc" id="available_times" style="">
                                                    <div class="time-today-block">
                                                        <a class="time-today-title" target="_blank" href="<?php echo site_url(); ?>/get-clinic-book-page-by-api-id?id=<?php echo isset($custom["api_id"][0]) ? $custom["api_id"][0] : 0; ?>">
                                                            <?php echo __('Available Times Today:'); ?>
                                                        </a>
                                                    </div>
                                                    <div class="info-btns get-in-line-buttons">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="current-work-hours">
                                            <h2 class="work-hours-header"><?php echo __('Today&#39;s Hours:'); ?></h2>
                                            <div class="work-hours">
                                                <?php
                                                $openHours = '';
                                                switch (current_time('l')) {
                                                    case 'Monday':
                                                        $openHours = isset($custom["clinic_monday"][0]) ? $custom["clinic_monday"][0] : '';
                                                        break;
                                                    case 'Tuesday':
                                                        $openHours = isset($custom["clinic_tuesday"][0]) ? $custom["clinic_tuesday"][0] : '';
                                                        break;
                                                    case 'Wednesday':
                                                        $openHours = isset($custom["clinic_wednesday"][0]) ? $custom["clinic_wednesday"][0] : '';
                                                        break;
                                                    case 'Thursday':
                                                        $openHours = isset($custom["clinic_thursday"][0]) ? $custom["clinic_thursday"][0] : '';
                                                        break;
                                                    case 'Friday':
                                                        $openHours = isset($custom["clinic_friday"][0]) ? $custom["clinic_friday"][0] : '';
                                                        break;
                                                    case 'Saturday':
                                                        $openHours = isset($custom["clinic_saturday"][0]) ? $custom["clinic_saturday"][0] : '';
                                                        break;
                                                    case 'Sunday':
                                                        $openHours = isset($custom["clinic_sunday"][0]) ? $custom["clinic_sunday"][0] : '';
                                                        break;
                                                }
                                                ?>
                                                <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/wall_clock.png" style="float:left;margin: 3px 14px 0 0;width: 22px;">
												<?php echo $openHours ?>
                                            </div>
                                            <h2 class="work-hours-footer"><?php //echo __('7 Days a Week'); ?></h2>
                                        </div>
                                    </div>
                                    <div id="map_canvas" style="height:436px"></div>

                                    <script type="text/javascript">
                                        //<![CDATA[
                                        jQuery(document).ready( function() {

                                            var updateTimes = function (hospital_id) {
                                                Clockwise.AvailableTimes(hospital_id, 0, 'json', 0);
                                                jQuery('body').on('clockwise_times_loaded', function (e, id, result) {
                                                    if (hospital_id == id) {
                                                        var hasAvailableTimes = false;
                                                        if (!((Clockwise.Times[id] === null) || jQuery.isEmptyObject(Clockwise.Times[id]))) {
                                                            jQuery.each(Clockwise.Times[id], function (index, value) {
                                                                if (value[1] !== null) {
                                                                    hasAvailableTimes = true;
                                                                    return false;
                                                                }
                                                            });
                                                        }
                                                        if (!hasAvailableTimes) {
                                                            Clockwise.AvailableTimes(id, result + 1, 'json', 0);
                                                        } else {
                                                            var timesToPrint = [];
                                                            var timesToPrintQty = 5;
                                                            var counter = 0;
                                                            var buttonsHtml = '';
                                                            var $currentListItem = jQuery('#available_times');
                                                            var baseUrl = '<?php echo site_url(); ?>';
                                                            jQuery.each(Clockwise.Times[id], function (index, value) {
                                                                if ((value[1] !== null) && (counter < timesToPrintQty)) {
                                                                    timesToPrint[counter] = value[0];
                                                                    counter++;
                                                                }
                                                            });
                                                            if (typeof (timesToPrint[0]) !== 'undefined') {
                                                                buttonsHtml +=
                                                                    '<a target="_blank" href="' + baseUrl + '/get-clinic-book-page-by-api-id?id=' + id + '&time=' + encodeURIComponent(timesToPrint[0]) + '">' +
                                                                    '<div class="btn btn-primary">' + timesToPrint[0] + '</div>' +
                                                                    '</a>';
                                                            }
                                                            if (typeof (timesToPrint[1]) !== 'undefined') {
                                                                buttonsHtml +=
                                                                    '<a target="_blank" href="' + baseUrl + '/get-clinic-book-page-by-api-id?id=' + id + '&time=' + encodeURIComponent(timesToPrint[1]) + '">' +
                                                                    '<div class="btn btn-primary">' + timesToPrint[1] + '</div>' +
                                                                    '</a>';
                                                            }
                                                            if (typeof (timesToPrint[2]) !== 'undefined') {
                                                                var timesList = '<ul class="add-times-dropdown-list">';
                                                                jQuery.each(timesToPrint, function (index, value) {
                                                                    if (index >= 2) {
                                                                        timesList +=
                                                                            '<li>' +
                                                                            '<a target="_blank" href="' + baseUrl + '/get-clinic-book-page-by-api-id?id=' + id + '&time=' + encodeURIComponent(value) + '">' +
                                                                            value +
                                                                            '</a>' +
                                                                            '</li>';
                                                                    }
                                                                });
                                                                timesList += '<li><a target="_blank" href="' + baseUrl + '/get-clinic-book-page-by-api-id?id=' + id + '">More</a></li>';
                                                                timesList += '</ul>';
                                                                buttonsHtml +=
                                                                    '<span class="add-times-dropdown">' +
                                                                    '<div class="btn btn-primary">+ TIMES -</div>' +
                                                                    timesList +
                                                                    '</span>';
                                                            }
                                                            $currentListItem.find('.get-in-line-buttons').html(
                                                                buttonsHtml
                                                            );
                                                        }
                                                    }
                                                });
                                            };

                                            var hospitalId = <?php echo isset($custom["api_id"][0]) ? $custom["api_id"][0] : 0; ?>;

                                            updateTimes(hospitalId);
                                            var interval = setInterval(function () {
                                                updateTimes(hospitalId);
                                            }, 30000);
                                        });

                                        function initialize() {
                                            var mapProp = {
                                                center: new google.maps.LatLng(<?php echo isset($custom["latitude"][0]) ? $custom["latitude"][0] : '0'; ?>,<?php echo isset($custom["longitude"][0]) ? $custom["longitude"][0] : '0'; ?>),
                                                zoom:16,
                                                mapTypeId:google.maps.MapTypeId.ROADMAP,
	                                            <?php if(wp_is_mobile()){echo "mapTypeControlOptions: {position: google.maps.ControlPosition.LEFT_BOTTOM},";}?>
                                                fullscreenControl: true
                                            };
                                            var map=new google.maps.Map(document.getElementById("map_canvas"),mapProp);
                                            var marker=new google.maps.Marker({
                                                position: new google.maps.LatLng(<?php echo isset($custom["latitude"][0]) ? $custom["latitude"][0] : '0'; ?>,<?php echo isset($custom["longitude"][0]) ? $custom["longitude"][0] : '0'; ?>),
                                                title: '<?php echo $addressString ?>',
                                                animation:google.maps.Animation.DROP
                                            });
                                            marker.setMap(map);
                                            var infowindow = new google.maps.InfoWindow({
                                                content:"<?php echo "<div class='pea-location-maker'><b style='color:#ED1A3B;height:50px;display width:100px;'>".$addressString."</b><br/>"."<a target='_blank' href='".site_url()."/get-clinic-book-page-by-api-id?id=".(isset($custom["api_id"][0]) ? $custom["api_id"][0] : '')."'><button style='padding:8px 15px;margin-top:5px;border-radius:3px;background-color:#274573;color:white'>Get in Line</button></a></div>"; ?>"
                                            });
                                            infowindow.open(map,marker);
                                            google.maps.event.addListener(marker,'click',function() {
                                                map.setZoom(18);
                                                map.setCenter(marker.getPosition());
                                            });
                                            google.maps.event.addListener(marker, 'click', toggleBounce);
                                        }
                                        function toggleBounce() {
                                            if (marker.getAnimation() != null) {
                                                marker.setAnimation(null);
                                            } else {
                                                marker.setAnimation(google.maps.Animation.BOUNCE);
                                            }
                                        }
                                        google.maps.event.addDomListener(window, 'load', initialize);
                                        google.maps.event.addDomListener(window, 'resize', initialize);

                                        //]]>
                                    </script>

                                    <div class="clinic_front">
                                        <img src="<?php the_post_thumbnail_url('single-post-thumbnail'); ?>"/>
                                    </div>
                                </div>

                                <?php
                                the_content();

                                wp_link_pages( array(
                                    'before' => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
                                    'after'  => '</div>',
                                ) );
                                ?>
                            </div><!-- .entry-content -->
                        </article><!-- #post-## -->
                        <?php
                            // If comments are open or we have at least one comment, load up the comment template.
                            if ( comments_open() || get_comments_number() ) :
                                comments_template();
                            endif;

                        endwhile; // End of the loop.
                        ?>
                    </div>
                    <?php else:?>
                        <div class="content-one-column content-one-column-mobile">
		                    <?php
		                    while ( have_posts() ) : the_post();
			                    $post = get_post();
			                    $custom = get_post_custom($post->ID);
			                    $addressString = stripslashes(isset($custom["address"][0]) ? $custom["address"][0] : '').', '
			                                     .stripslashes(isset($custom["city"][0]) ? $custom["city"][0] : '').', '
			                                     .stripslashes(isset($custom["state"][0]) ? $custom["state"][0] : '').'  '
			                                     .stripslashes(isset($custom["zip_code"][0]) ? $custom["zip_code"][0] : '');
			                    ?>

                                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                    <div class="entry-content">
                                        <div class="loc-left-panel top_area">
                                            <h4>
							                    <?php echo stripslashes(isset($custom["clinic_name"][0]) ? $custom["clinic_name"][0] : ''); ?>  <span>Center</span>
                                            </h4>
                                            <div class="border"><p></p></div>
                                            <div class="loc-left-panel m-loc-left-panel address_email">
                                                <ul class="address" id="detail">
                                                    <li>
                                                        <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/address.png"><p><span>Address:</span> <?php
					                                        echo stripslashes(isset($custom["address"][0]) ? $custom["address"][0] : '')
					                                             .'<br/>'.stripslashes(isset($custom["city"][0]) ? $custom["city"][0] : '')
					                                             .', '.stripslashes(isset($custom["state"][0]) ? $custom["state"][0] : '')
					                                             .'  '.stripslashes(isset($custom["zip_code"][0]) ? $custom["zip_code"][0] : ''); ?>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/fone.png"><p><span style="width:auto">Phone:</span>&nbsp; <?php echo isset($custom["phone"][0]) ? $custom["phone"][0] : ''; ?></p>
                                                    </li>
                                                    <li>
                                                        <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/fax.png"><p><span style="width:auto">Fax:</span>&nbsp;<?php echo isset($custom["fax"][0]) ? $custom["fax"][0] : ''; ?></p>
                                                    </li>
                                                    <li>
                                                        <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/email.png"><p><span>Email:</span> <?php echo isset($custom["email"][0]) ? $custom["email"][0] : ''; ?></p>
                                                    </li>
                                                    <li>
                                                        <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/holidays.png"><p><span>Holidays:</span><samp> We are open <?php echo isset($custom["clinic_open_days"][0]) ? $custom["clinic_open_days"][0] : ''; ?> days per year!</samp> Closed: <?php echo isset($custom["clinic_close_days"][0]) ? $custom["clinic_close_days"][0] : ''; ?>.</p>
                                                    </li>
			                                        <?php if((isset($custom["services"][0]) ? $custom["services"][0] : '') != '') {?>
                                                        <li style="border-bottom:0">
                                                            <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/icon-4.png" style="height: 27px;"><p><span style="width:auto">Services Offered:</span><br/> <?php echo nl2br(isset($custom["services"][0]) ? $custom["services"][0] : ''); ?></p>
                                                        </li>
			                                        <?php } ?>
                                                </ul>
                                            </div>
                                            <div class="loc-right-panel m-loc-right-panel address_email">
                                                <div class="waing_time">
                                                    <div class="get-inline">
                                                        <h2 class="get-inline-header">
                                                            <?php echo __('Check In Online'); ?>
                                                        </h2>
                                                        <a target="_blank" href="<?php echo site_url()."/get-clinic-book-page-by-api-id?id=".(isset($custom["api_id"][0]) ? $custom["api_id"][0] : ''); ?>">
                                                            <button><?php echo __('Check In'); ?></button>
                                                        </a>
                                                    </div>
                                                    <div class="addresses">
                                                        <div class="row-fluid_maps">
                                                            <div class="timings-loc" id="available_times" style="">
                                                                <div class="time-today-block">
                                                                    <a class="time-today-title" target="_blank" href="<?php echo site_url(); ?>/get-clinic-book-page-by-api-id?id=<?php echo isset($custom["api_id"][0]) ? $custom["api_id"][0] : 0; ?>">
                                                                        <?php echo __('Available Times Today:'); ?>
                                                                    </a>
                                                                </div>
                                                                <div class="info-btns get-in-line-buttons">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="current-work-hours">
                                                        <h2 class="work-hours-header"><?php echo __('Open:'); ?></h2>
                                                        <div class="work-hours">
                                                            <?php
                                                            $openHours = '';
                                                            switch (current_time('l')) {
                                                                case 'Monday':
                                                                    $openHours = isset($custom["clinic_monday"][0]) ? $custom["clinic_monday"][0] : '';
                                                                    break;
                                                                case 'Tuesday':
                                                                    $openHours = isset($custom["clinic_tuesday"][0]) ? $custom["clinic_tuesday"][0] : '';
                                                                    break;
                                                                case 'Wednesday':
                                                                    $openHours = isset($custom["clinic_wednesday"][0]) ? $custom["clinic_wednesday"][0] : '';
                                                                    break;
                                                                case 'Thursday':
                                                                    $openHours = isset($custom["clinic_thursday"][0]) ? $custom["clinic_thursday"][0] : '';
                                                                    break;
                                                                case 'Friday':
                                                                    $openHours = isset($custom["clinic_friday"][0]) ? $custom["clinic_friday"][0] : '';
                                                                    break;
                                                                case 'Saturday':
                                                                    $openHours = isset($custom["clinic_saturday"][0]) ? $custom["clinic_saturday"][0] : '';
                                                                    break;
                                                                case 'Sunday':
                                                                    $openHours = isset($custom["clinic_sunday"][0]) ? $custom["clinic_sunday"][0] : '';
                                                                    break;
                                                            }
                                                            ?>
                                                            <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/wall_clock.png" style="float:left;margin: 3px 14px 0 0;width: 22px;">
                                                            <?php echo $openHours; ?>
                                                        </div>
                                                        <h2 class="work-hours-footer"><?php echo __('7 Days a Week'); ?></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="loc-right-panel top_area top_area_bottom">
                                            <h4>Location</h4>
                                            <div class="border"><p></p></div>
                                            <div class="loc-right-panel m-loc-bottom address_email">
                                                <div id="map_canvas"></div>

                                                <script type="text/javascript">
                                                    //<![CDATA[

                                                    jQuery(document).ready( function() {
                                                        var updateTimes = function (hospital_id) {
                                                            Clockwise.AvailableTimes(hospital_id, 0, 'json', 0);
                                                            jQuery('body').on('clockwise_times_loaded', function (e, id, result) {
                                                                if (hospital_id == id) {
                                                                    var hasAvailableTimes = false;
                                                                    if (!((Clockwise.Times[id] === null) || jQuery.isEmptyObject(Clockwise.Times[id]))) {
                                                                        jQuery.each(Clockwise.Times[id], function (index, value) {
                                                                            if (value[1] !== null) {
                                                                                hasAvailableTimes = true;
                                                                                return false;
                                                                            }
                                                                        });
                                                                    }
                                                                    if (!hasAvailableTimes) {
                                                                        Clockwise.AvailableTimes(id, result + 1, 'json', 0);
                                                                    } else {
                                                                        var timesToPrint = [];
                                                                        var timesToPrintQty = 5;
                                                                        var counter = 0;
                                                                        var buttonsHtml = '';
                                                                        var $currentListItem = jQuery('#available_times');
                                                                        var baseUrl = '<?php echo site_url(); ?>';
                                                                        jQuery.each(Clockwise.Times[id], function (index, value) {
                                                                            if ((value[1] !== null) && (counter < timesToPrintQty)) {
                                                                                timesToPrint[counter] = value[0];
                                                                                counter++;
                                                                            }
                                                                        });
                                                                        if (typeof (timesToPrint[0]) !== 'undefined') {
                                                                            buttonsHtml +=
                                                                                '<a target="_blank" href="' + baseUrl + '/get-clinic-book-page-by-api-id?id=' + id + '&time=' + encodeURIComponent(timesToPrint[0]) + '">' +
                                                                                '<div class="btn btn-primary">' + timesToPrint[0] + '</div>' +
                                                                                '</a>';
                                                                        }
                                                                        if (typeof (timesToPrint[1]) !== 'undefined') {
                                                                            buttonsHtml +=
                                                                                '<a target="_blank" href="' + baseUrl + '/get-clinic-book-page-by-api-id?id=' + id + '&time=' + encodeURIComponent(timesToPrint[1]) + '">' +
                                                                                '<div class="btn btn-primary">' + timesToPrint[1] + '</div>' +
                                                                                '</a>';
                                                                        }
                                                                        if (typeof (timesToPrint[2]) !== 'undefined') {
                                                                            var timesList = '<ul class="add-times-dropdown-list">';
                                                                            jQuery.each(timesToPrint, function (index, value) {
                                                                                if (index >= 2) {
                                                                                    timesList +=
                                                                                        '<li>' +
                                                                                        '<a target="_blank" href="' + baseUrl + '/get-clinic-book-page-by-api-id?id=' + id + '&time=' + encodeURIComponent(value) + '">' +
                                                                                        value +
                                                                                        '</a>' +
                                                                                        '</li>';
                                                                                }
                                                                            });
                                                                            timesList += '<li><a target="_blank" href="' + baseUrl + '/get-clinic-book-page-by-api-id?id=' + id + '">More</a></li>';
                                                                            timesList += '</ul>';
                                                                            buttonsHtml +=
                                                                                '<span class="add-times-dropdown">' +
                                                                                '<div class="btn btn-primary">+ TIMES -</div>' +
                                                                                timesList +
                                                                                '</span>';
                                                                        }
                                                                        $currentListItem.find('.get-in-line-buttons').html(
                                                                            buttonsHtml
                                                                        );
                                                                    }
                                                                }
                                                            });
                                                        };

                                                        var hospitalId = <?php echo isset($custom["api_id"][0]) ? $custom["api_id"][0] : 0; ?>;

                                                        updateTimes(hospitalId);
                                                        var interval = setInterval(function () {
                                                            updateTimes(hospitalId);
                                                        }, 30000);
                                                    });

                                                    function initialize() {
                                                        var mapProp = {
                                                            center: new google.maps.LatLng(<?php echo isset($custom["latitude"][0]) ? $custom["latitude"][0] : '0'; ?>,<?php echo isset($custom["longitude"][0]) ? $custom["longitude"][0] : '0'; ?>),
                                                            zoom:16,
                                                            mapTypeId:google.maps.MapTypeId.ROADMAP,
                                                           <?php if(wp_is_mobile()){echo "mapTypeControlOptions: {position: google.maps.ControlPosition.LEFT_BOTTOM},";}?>
                                                            fullscreenControl: true
                                                        };
                                                        var map=new google.maps.Map(document.getElementById("map_canvas"),mapProp);
                                                        var marker=new google.maps.Marker({
                                                            position: new google.maps.LatLng(<?php echo isset($custom["latitude"][0]) ? $custom["latitude"][0] : '0'; ?>,<?php echo isset($custom["longitude"][0]) ? $custom["longitude"][0] : '0'; ?>),
                                                            title: '<?php echo $addressString ?>',
                                                            animation:google.maps.Animation.DROP
                                                        });
                                                        marker.setMap(map);
                                                        var infowindow = new google.maps.InfoWindow({
                                                            content:"<?php echo "<div class='pea-location-maker'><b style='color:#ED1A3B;height:50px;display width:100px;'>".$addressString."</b><br/>"."<a target='_blank' href='".site_url()."/get-clinic-book-page-by-api-id?id=".(isset($custom["api_id"][0]) ? $custom["api_id"][0] : '')."'><button style='padding:8px 15px;margin-top:5px;border-radius:3px;background-color:#274573;color:white'>Get in Line</button></a></div>"; ?>"
                                                        });
                                                        infowindow.open(map,marker);
                                                        google.maps.event.addListener(marker,'click',function() {
                                                            map.setZoom(18);
                                                            map.setCenter(marker.getPosition());
                                                        });
                                                        google.maps.event.addListener(marker, 'click', toggleBounce);
                                                        google.maps.event.addListener(marker, 'resize', toggleBounce);
                                                    }
                                                    function toggleBounce() {
                                                        if (marker.getAnimation() != null) {
                                                            marker.setAnimation(null);
                                                        } else {
                                                            marker.setAnimation(google.maps.Animation.BOUNCE);
                                                        }
                                                    }
                                                    google.maps.event.addDomListener(window, 'load', initialize);
                                                    google.maps.event.addDomListener(window, 'resize', initialize);
                                                    //]]>
                                                </script>

                                                <div class="clinic_front">
                                                    <img src="<?php the_post_thumbnail_url('single-post-thumbnail'); ?>"/>
                                                </div>
                                            </div>
                                        </div>
					                    <?php
					                    the_content();

					                    wp_link_pages( array(
						                    'before' => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
						                    'after'  => '</div>',
					                    ) );
					                    ?>
                                    </div><!-- .entry-content -->
                                </article><!-- #post-## -->
			                    <?php
			                    // If comments are open or we have at least one comment, load up the comment template.
			                    if ( comments_open() || get_comments_number() ) :
				                    comments_template();
			                    endif;

		                    endwhile; // End of the loop.
		                    ?>
                        </div>
                    <?php endif;?>
                </div>
            </main><!-- #main -->
        </div><!-- #primary -->
    </div><!-- .wrap -->

<?php get_footer();
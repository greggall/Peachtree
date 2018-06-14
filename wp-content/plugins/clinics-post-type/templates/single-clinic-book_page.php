<?php

/*
 * Template Name: Clinic Booking Page Template
 * Description: A Page Template for Clinic Booking
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
				                        'booking',                             // Menu Location to use for breadcrumb
				                        ' / ',                        // separator between each breadcrumb
				                        '<p class="menu-breadcrumb">',      // output before the breadcrumb
				                        '</p>'                              // output after the breadcrumb
			                        );
		                        }
		                        ?>
                            </div>
                        <?php endif;?>
                        <h1 class="entry-title"><?php echo __('Online Check In'); ?></h1>
                        <?php twentyseventeen_edit_link( get_the_ID() ); ?>
	                    <?php if(!wp_is_mobile()):?>
                            <div class="breadcrumb">
			                    <?php
			                    if (function_exists('menu_breadcrumb')) {
				                    menu_breadcrumb(
					                    'booking',                             // Menu Location to use for breadcrumb
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
                    <div class="content-one-column">
                        <?php
                        while ( have_posts() ) : the_post();
                            $post = get_post();
                            $custom = get_post_custom($post->ID);
                            $postApiId = isset($custom["book_api_id"][0]) ? $custom["book_api_id"][0] : 0;
                            $posts = get_posts(array(
                                'numberposts' => 1,
                                'post_type'	=> 'clinic_page',
                                'meta_key' => 'api_id',
                                'meta_value' => $postApiId,
                            ));
                            if (count($posts)) {
                                $postClinic = $posts[0];
                                $customClinic = get_post_custom($postClinic->ID);
                            } else {
                                wp_redirect(site_url());
                                exit;
                            }

                            $getTimeValue = isset($_GET['time']) ? $_GET['time'] : null;

                            $str = stripslashes(isset($customClinic["address"][0]) ? $customClinic["address"][0] : '') . ', ' . stripslashes(isset($customClinic["city"][0]) ? $customClinic["city"][0] : '') . ', ' . stripslashes(isset($customClinic["state"][0]) ? $customClinic["state"][0] : '') . ' ' . stripslashes(isset($customClinic["zip_code"][0]) ? $customClinic["zip_code"][0] : '');
                            ?>

                            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                <div class="entry-content top">

                                    <form class="formSubmitter" method='post'>
                                        <div class="loc-left-panel address_email">
                                            <h4 class="hide-after-submit"><?php echo stripslashes(isset($customClinic["clinic_name"][0]) ? $customClinic["clinic_name"][0] : ''); ?> <span>Center</span></h4>
                                            <h4 class="show-after-submit">Your Time<span> Is Confirmed</span></h4>
                                            <div class="border"><p></p></div>

                                            <div class="appointment-result-container show-after-submit">
                                                 <div id="appointment-result-data">
                                                 </div>
                                                <div class="appointment-result-static">
                                                    <h2>What You Need</h2>
                                                    <p>Please bring a valid photo ID and any insurance cards (if applicable).</p>
                                                    <h3>New Patients</h3>
                                                    <p class="no-margin-bottom">Save 15 minutes with paperwork in the clinic. Print and complete this forms before you arrive and we can see you even faster!</p>
                                                    <p class="no-margin-bottom-top"><a href="<?php get_pdf_registration_file_link(); ?>">Registration Form</a></p>
                                                    <p class="no-margin-bottom-top margin-bottom"><a href="<?php get_pdf_consent_file_link(); ?>">Minor Consent Form</a></p>
                                                    <h3>Contact Information</h3>
                                                    <p>If you have a medical emergency, call 911 or go to the nearest emergency room immediately</p>
                                                    <p>If you have any questions, please call us at 770-974-3911.</p>
                                                    <p>We look forward to see you soon.</p>

                                                </div>
                                            </div>

                                            <div class="est-wait-time hide-after-submit">
                                                <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/alert_clock-40.png">
                                                <span class="est-wait-time-container" id="current_wait_simple_<?php echo isset($customClinic["api_id"][0]) ? $customClinic["api_id"][0] : ''; ?>"></span>
                                                <span class="est-wait-time-text">(current estimated wait time)</span>
                                            </div>

                                            <div class="form-input-group hide-after-submit">

                                                <ul id="errors"></ul>

                                                <div class="form-group-left form-group-left-2 form-group-left-3">
                                                    <p>
                                                        <span class="hint--bottom" aria-label="Select a Date">
                                                            <span class="flied flied_b">
                                                                <select name="date" id="dateAppender" class="">
                                                                    <option disabled selected>Select a Date</option>
                                                                </select>
                                                                <span class="starik">*</span>
                                                            </span>
                                                        </span>
                                                    </p>
                                                    <p>
                                                        <span class="hint--bottom" aria-label="Please select Time">
                                                            <span class="flied flied_b">
                                                                <select name="time" id="timeAppender" class="">
                                                                    <option disabled selected>Please select Time</option>
                                                                </select>
                                                                <span class="starik">*</span>
                                                            </span>
                                                        </span>
                                                    </p>
                                                    <p>
                                                        <span class="hint--bottom" aria-label="Please Select a Reason">
                                                            <label for="options">Select Reason</label>
                                                            <span class="flied flied_b">
                                                                <select name="options" id="options" required="required">
                                                                    <option disabled selected value="">Select Reason</option>
                                                                </select>
                                                                <span class="starik">*</span>
                                                            </span>
                                                        </span>
                                                    </p>
                                                    <p>
                                                        <span class="hint--bottom" aria-label="Patient First Name">
                                                            <span class="flied flied_b">
                                                                <input type="text" id="first_name" value="" required="required" class="" placeholder="Patient First Name">
                                                                <span class="starik">*</span>
                                                            </span>
                                                        </span>
                                                    </p>
                                                    <p>
                                                        <span class="hint--bottom" aria-label="Patient Last Name">
                                                            <span class="flied flied_b">
                                                                <input type="text" id="last_name" value="" required="required" class="" placeholder="Patient Last Name">
                                                                <span class="starik">*</span>
                                                            </span>
                                                        </span>
                                                    </p>
                                                    <p>
                                                        <span class="hint--bottom" aria-label="Email">
                                                            <span class="flied flied_b">
                                                                <input type="email" id="email" value="" class="" placeholder="Email">
                                                            </span>
                                                        </span>
                                                    </p>
                                                    <p>
                                                        <span class="hint--bottom" aria-label="Cell Phone Number">
                                                            <span class="flied flied_b">
                                                                <input type="text" id="phone" min="10" max="10" value="" placeholder="Cell Phone Number">
                                                                <span class="starik">*</span>
                                                            </span>
                                                        </span>
                                                    </p>
                                                    <p>
                                                        <span class="hint--bottom" aria-label="Please choose a patient type">
                                                            <span class="flied flied_b">
                                                                <select name="patientType" id="patient_type" required="required" class="">
                                                                    <option disabled selected>Choose a Patient Type</option>
                                                                    <option value="true">New Patient</option>
                                                                    <option value="false">Existing Patient</option>
                                                                </select>
                                                                <span class="starik">*</span>
                                                            </span>
                                                        </span>
                                                    </p>
                                                    <p>
                                                        <span class="hint--bottom" aria-label="Date of Birth . Format : mm/dd/yyyy">
                                                            <span class="flied flied_b">
                                                                <input type="text" id="dob" placeholder="Date of Birth" required="required" class="" value="">
                                                                <span class="starik">*</span>
                                                            </span>
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="col-lg-12">
                                                    <p>We'll send you a text message when it's time to show up.</p>
                                                    <p>
                                                        <input min="0" type="number" id="pager_minutes" name="pager_minutes" value="20"><span>minutes before my visit</span>
                                                    </p>
                                                </div>

                                                <div class="form-send hide-after-submit">

                                                    <div class="check-before">
                                                        <input type="checkbox" required="required" id="callHelp"><label> I have received the information: <a target="_blank" href="http://www.911.gov/whentocall911.html">on when to call 911</a></label>
                                                    </div>
                                                    <div class="btn-btn">
														
                                                        <button class="btn-sent" style="background-color: #274573 ! important" onclick="gtag('event', 'click', {
														  'event_category': 'Confirm Me Button',
														  'event_label': '<?php echo the_title(); ?>'
														});">Confirm me</button>
												
                                                    </div>
                                                </div>
                                                <div>
                                                    <div id="loading" class=" hidden errorOptimizer"></div>
                                                    <span id="closespaner" style="display: none;">Close it</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="loc-right-panel address_email">
                                            <div class="loc-right-panel top_area">
                                                <h4>Location</h4>
                                                <div class="border"><p></p></div>
                                            </div>
                                            <div class="loc-right-address">
                                                <ul class="address top hide-after-submit" id="detail">
                                                    <li>
                                                        <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/address.png"><p><span>Address:</span> <?php
				                                            echo stripslashes(isset($customClinic["address"][0]) ? $customClinic["address"][0] : '')
				                                                 .'<br/>'.stripslashes(isset($customClinic["city"][0]) ? $customClinic["city"][0] : '')
				                                                 .', '.stripslashes(isset($customClinic["state"][0]) ? $customClinic["state"][0] : '')
				                                                 .'  '.stripslashes(isset($customClinic["zip_code"][0]) ? $customClinic["zip_code"][0] : ''); ?>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/fone.png">
                                                        <p>
                                                            <span style="width:auto">Phone:</span>&nbsp; <?php echo isset($customClinic["phone"][0]) ? $customClinic["phone"][0] : ''; ?>
                                                            <span class="change-location">
                                                            <a href="<?php echo site_url() . '/location/'; ?>">Change Location</a>
                                                        </span>
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="directions-links show-after-submit">
                                                <div class="left">
                                                    <a target="_blank" href="https://www.google.com/maps/dir/Current+Location/<?php echo urlencode($str); ?>/@<?php echo isset($customClinic["latitude"][0]) ? $customClinic["latitude"][0] : '0'; ?>, <?php echo isset($customClinic["longitude"][0]) ? $customClinic["longitude"][0] : '0'; ?>">Get Directions</a>
                                                </div>
                                                <div class="right">
                                                    <!--<a href="javascript:" class="send-to-phone-link">Send To Phone</a>-->
                                                </div>
                                                <div class="bottom">
                                                    <span>Need an <a target="_blank" href='https://get.uber.com/open_app'>Uber pick up?</a></span>
                                                </div>
                                            </div>

                                            <ul class="address show-after-submit" id="detail">
                                                <li>
                                                    <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/address.png"><p><span>Address:</span> <?php
                                                        echo stripslashes(isset($customClinic["address"][0]) ? $customClinic["address"][0] : '')
                                                            .'<br/>'.stripslashes(isset($customClinic["city"][0]) ? $customClinic["city"][0] : '')
                                                            .', '.stripslashes(isset($customClinic["state"][0]) ? $customClinic["state"][0] : '')
                                                            .'  '.stripslashes(isset($customClinic["zip_code"][0]) ? $customClinic["zip_code"][0] : ''); ?>
                                                    </p>
                                                </li>
                                                <li style="border-bottom:0; padding-bottom: 5px;">
                                                    <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/fone.png"><p><span style="width:auto">Phone:</span>&nbsp; <?php echo isset($customClinic["phone"][0]) ? $customClinic["phone"][0] : ''; ?></p>
                                                </li>
                                            </ul>

                                            <div id="map_canvas" style="height:436px"></div>

                                            <div class="our-goal" id="director">
                                            </div>
                                            <div class="clinic_front"><img src="<?php echo get_the_post_thumbnail_url($postClinic->ID, 'single-post-thumbnail'); ?>">
                                            </div>

                                            <ul class="address show-after-submit" id="detail">
                                                <?php if((isset($customClinic["services"][0]) ? $customClinic["services"][0] : '') != '') {?>
                                                    <li style="border-bottom:0">
                                                        <img src="<?php echo site_url(); ?>/wp-content/themes/peach/assets/images/icon-4.png" style="height: 27px;"><p><span style="width:auto">Services Offered:</span><br/> <?php echo nl2br(isset($customClinic["services"][0]) ? $customClinic["services"][0] : ''); ?></p>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>

                                        <div style="clear: both;"></div>
                                        <?php if(!wp_is_mobile()):?>
                                        <div>
                                            <div id="loading" class=" hidden errorOptimizer"></div>
                                            <span id="closespaner" style="display: none;">Close it</span>
                                        </div>
                                        <?php endif;?>
                                    </form>
                                    <div class="loc-left-panel top_area hide-after-submit">
                                        <p>Please click <a href="<?php echo site_url(); ?>/insurance/">here</a> for information on insurances accepted.</p>
                                        <p>Please call the center if the patient under 2 years old before visiting.</p>
                                        <p>Finally, please make sure you don`t <a target="_blank" href="http://www.911.gov/whentocall911.html">need to call 911</a></p>
                                    </div>

                                    <div style="clear:both"></div>


                                    <script type="text/javascript">
                                    //<![CDATA[
                                        jQuery(document).ready( function() {
                                            jQuery('body').on('clockwise_waits_loaded', function (e, id) {
                                                // This one populates the basic wait display
                                                var waitTime = parseInt(jQuery(Clockwise.Waits[id]).text());
                                                if (isNaN(waitTime)) {
                                                    jQuery('#current_wait_simple_'+id).text('Closed');
                                                } else {
                                                    if (waitTime == 1) {
                                                        jQuery('#current_wait_simple_'+id).text(waitTime + ' minute');
                                                    } else {
                                                        jQuery('#current_wait_simple_'+id).text(waitTime + ' minutes');
                                                    }
                                                }

                                            });
                                            loadAllWaits();

                                            jQuery('.send-to-phone-link').on('click', function(e) {
                                                // If it's an iPhone..
                                                if ((navigator.platform.indexOf("iPhone") != -1)
                                                    || (navigator.platform.indexOf("iPod") != -1)
                                                    || (navigator.platform.indexOf("iPad") != -1)) {
                                                    window.open("maps://maps.google.com/maps?daddr=<?php echo urlencode($str); ?>&ll=<?php echo isset($customClinic["latitude"][0]) ? $customClinic["latitude"][0] : '0'; ?>, <?php echo isset($customClinic["longitude"][0]) ? $customClinic["longitude"][0] : '0'; ?>");
                                                } else {
                                                    window.open("http://maps.google.com/maps?daddr=<?php echo urlencode($str); ?>&ll=<?php echo isset($customClinic["latitude"][0]) ? $customClinic["latitude"][0] : '0'; ?>, <?php echo isset($customClinic["longitude"][0]) ? $customClinic["longitude"][0] : '0'; ?>");
                                                }
                                            })
                                        });
                                        function loadAllWaits() {
                                            Clockwise.CurrentWait('<?php echo isset($customClinic["api_id"][0]) ? $customClinic["api_id"][0] : ''; ?>', 'html');
                                            // To Add additional wait times for other clinics, copy line above with different ID.
                                            setTimeout(function(){loadAllWaits()},3000);
                                        }

                                        function initialize() {
                                            var mapProp = {
                                                center: new google.maps.LatLng(<?php echo isset($customClinic["latitude"][0]) ? $customClinic["latitude"][0] : '0'; ?>,<?php echo isset($customClinic["longitude"][0]) ? $customClinic["longitude"][0] : '0'; ?>),
                                                zoom: 16,
	                                            <?php if(wp_is_mobile()){echo "mapTypeControlOptions: {position: google.maps.ControlPosition.LEFT_BOTTOM},";}?>
                                                mapTypeId: google.maps.MapTypeId.ROADMAP
                                            };
                                            var map = new google.maps.Map(document.getElementById("map_canvas"), mapProp);
                                            var marker = new google.maps.Marker({
                                                position: new google.maps.LatLng(<?php echo isset($customClinic["latitude"][0]) ? $customClinic["latitude"][0] : '0'; ?>,<?php echo isset($customClinic["longitude"][0]) ? $customClinic["longitude"][0] : '0'; ?>),

                                                title: '<?php echo $str ?>',
                                                animation: google.maps.Animation.DROP
                                            });
                                            marker.setMap(map);

                                            var infowindow = new google.maps.InfoWindow({
                                                content: "<?php echo "<div class='pea-location-maker'><b style='color:#ED1A3B;height:50px;display width:100px;'>" . $str . "</b><br>"; ?></div>"
                                            });
                                            infowindow.open(map, marker);
                                            google.maps.event.addListener(marker, 'click', function () {
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

                                        jQuery(document).ready(function () {
                                            var url_page = jQuery(location).attr("href");
                                            var parts = url_page.split("/");
                                            var clinic_id = <?php echo $postApiId; ?>;
                                            form_data = new FormData();
                                            form_data.append('clinic_id', clinic_id);
                                            form_data.append('clinic', clinic_id);
                                            jQuery.ajax({
                                                url: '<?php echo site_url(); ?>'+ "/get-clinic-booking-reason-ajax",
                                                type: 'POST',
                                                data: form_data,
                                                processData: false,
                                                contentType: false,
                                                success: function (res) {
                                                    var sbj = jQuery.parseJSON(res);
                                                    /*  console.log('reasons Request');
                                                     console.log(sbj);*/
                                                    var $reasonOptionsSelect = jQuery('#options');
                                                    jQuery.each(sbj, function (key, val) {
                                                        jQuery.each(val, function (vals, k) {
                                                            $reasonOptionsSelect.append('<option data-clinicidreason="<?php echo $postApiId; ?>" id="' + k.id + '" data-queueid="' + k.appointment_queue_id + '" value="' + k.description + '">' + k.description + '</option>');
                                                        })
                                                    });
                                                    $reasonOptionsSelect.val($reasonOptionsSelect.find('option:eq(1)').val()).trigger('change');
                                                },
                                                error: function (error) {

                                                }
                                            });
                                        });

                                        var globalDataArray = [];
                                        var inc = 0;
                                        jQuery('#options').on('change', function () {
                                            var firstAppenderValue = 0;
                                            var firstDateSelec = 0;
                                            jQuery('#dateAppender').empty();
                                            jQuery('#timeAppender').empty();
                                            var reason = this.value;
                                            //  var que_id = jQuery(this).find(':selected').attr('data-queueid');

                                            var que_id = jQuery(this).find(':selected').data('queueid');
                                            if (typeof(que_id) == 'undefined') {
                                                jQuery('#dateAppender').append('<option>Please Select a Date</option>');
                                                jQuery('#timeAppender').append('<option>Please select Time</option>');
                                                return;
                                            }

                                            var clinic_id = <?php echo $postApiId; ?>;
                                            form_data = new FormData();
                                            form_data.append('clinic_id', clinic_id);
                                            form_data.append('queue_id', que_id);
                                            form_data.append('reason', reason);
                                            jQuery.ajax({
                                                url: '<?php echo site_url(); ?>' + "/get-clinic-booking-times-ajax",
                                                type: 'POST',
                                                data: form_data,
                                                processData: false,
                                                contentType: false,
                                                success: function (res) {
                                                    //  console.log(res);
                                                    /*console.log( JSON.parse(res));*/

                                                    jQuery.each(JSON.parse(res), function (key, value) {
                                                        if (jQuery.type(value) == 'array') {
                                                            /* if its an array */
                                                            jQuery.each(value, function (dtime, ddata) {
                                                                var disTime = ddata.display_time;
                                                                var time = ddata.time;
                                                                var providerID = ddata.provider_id;
                                                                var appQueID = ddata.appointment_queue_id;
                                                                var hospID = ddata.hospital_id;
                                                                globalDataArray[inc] = ddata;
                                                                inc += 1;
                                                                /* extracting time by date wise and appending to date select wise*/
                                                                var splitTimer = time.split('T');
                                                                var innerHtmlOfDateAppender = jQuery('#dateAppender').html();

                                                                /* if there is a array */
                                                                if (innerHtmlOfDateAppender.indexOf(splitTimer[0]) < 0) {
                                                                    firstDateSelec++;
                                                                    /* rearranging the display Time */
                                                                    //console.log(splitTimer[0]);
                                                                    var chunks = splitTimer[0].split('-');
                                                                    var NewDateAppender = chunks[1] + '/' + chunks[2] + '/' + chunks[0];

                                                                    jQuery('#dateAppender').append(
                                                                        "<option " + ( (firstDateSelec == 1 ) ? 'selected="selected"' : '' ) + " value='" + time + "' data-date='" + splitTimer[0] + "'>" +
                                                                            /*splitTimer[0]*/ NewDateAppender + "</option>");
                                                                    firstAppenderValue = 1;

                                                                    /* now selecting the first hour of the date */
                                                                    var Cval = jQuery('#dateAppender :selected').val();

                                                                    /* looping through array */
                                                                    jQuery('#timeAppender').empty();
                                                                    var matchingValue = Cval.split('T');
                                                                    var first = true;
                                                                    var selectedValue = null;
                                                                <?php if ($getTimeValue): ?>
                                                                    first = false;
                                                                    selectedValue = '<?php echo urldecode($getTimeValue); ?>';
                                                                <?php endif; ?>
                                                                    jQuery.each(globalDataArray, function (dapIndex, dapValue) {
                                                                        if (dapValue.time.indexOf(matchingValue[0]) >= 0) {
                                                                            jQuery('#timeAppender').append("<option value='" + dapValue.time + "' data-time='" + dapValue.time + "' " + (((first == true) || (selectedValue == dapValue.display_time)) ? 'selected="selected"' : '') + ">" + dapValue.display_time + "</option>");
                                                                            first = false;
                                                                        }
                                                                    });
                                                                }
                                                            });

                                                        } else {
                                                            /* if its not an array  */
                                                        }
                                                    });
                                                },
                                                error: function (error) {
                                                }
                                            });
                                        });


                                        /* now extracting all values of hours have same data */
                                        jQuery("#dateAppender").on('change', function () {
                                            /* looping through array */
                                            jQuery('#timeAppender').empty();
                                            var matchingValue = this.value.split('T');
                                            var first = true;
                                            jQuery.each(globalDataArray, function (dapIndex, dapValue) {
                                                if (dapValue.time.indexOf(matchingValue[0]) >= 0) {
                                                    jQuery('#timeAppender').append("<option value='" + dapValue.time + "' data-time='" + dapValue.time + "' " + ((first == true) ? 'selected="selected"' : '') + ">" + dapValue.display_time + "</option>");
                                                    first = false;
                                                }
                                            });
                                        });

                                        function save_appointment(event) {
                                            var errorsDefault = false;
                                            event.preventDefault();
                                            jQuery('#loading').removeClass('hidden');

                                            jQuery('.form-input-group input,select').removeClass('error');
                                            jQuery("#errors").empty();
                                            var reason_val = jQuery('#options').val();
                                            var reason_id = jQuery('#options').children(":selected").attr("id");


                                            var url_page = jQuery(location).attr("href");
                                            var parts = url_page.split("/");
                                            var clinic_id = <?php echo $postApiId; ?>;
                                            //
                                            var first_name = jQuery('#first_name').val();
                                            var last_name = jQuery('#last_name').val();
                                            var date_time = jQuery('#timeAppender option:selected').val();
                                            var patient_type = jQuery('#patient_type option:selected').val();
                                            var email = jQuery('#email').val();
                                            var phone = jQuery('#phone').val();
                                            var dob = jQuery('#dob').val();
                                            var pagerMinutes = jQuery('#pager_minutes').val();
                                            var date_formatted = jQuery('#dateAppender option:selected').text();
                                            var time_formatted = jQuery('#timeAppender option:selected').text();

                                            form_data = new FormData();
                                            form_data.append('clinic_id', clinic_id);
                                            form_data.append('reason_val', reason_val);
                                            form_data.append('reason_id', reason_id);
                                            form_data.append('first_name', first_name);
                                            form_data.append('last_name', last_name);
                                            form_data.append('date_time', date_time);
                                            form_data.append('patient_type', patient_type);
                                            form_data.append('email', email);
                                            form_data.append('phone', phone);
                                            form_data.append('dob', dob);
                                            form_data.append('pager_minutes', pagerMinutes);
                                            /* if there is any empty field left */

                                            if (jQuery('#first_name').val() == '') {
                                                errorsDefault = true;
                                                jQuery("#first_name").addClass('error');
                                                jQuery('#errors').append("<li>First name is Required</li>");
                                            }
                                            if (!jQuery('#last_name').val()) {
                                                errorsDefault = true;
                                                jQuery("#last_name").addClass('error');
                                                jQuery('#errors').append("<li>Last name is Required</li>");
                                            }
                                            if (!jQuery('#phone').val()) {
                                                errorsDefault = true;
                                                jQuery("#phone").addClass('error');
                                                jQuery('#errors').append("<li>Phone Number is Required</li>");
                                            }
                                            if (!jQuery('#dob').val()) {
                                                errorsDefault = true;
                                                jQuery("#dob").addClass('error');
                                                jQuery('#errors').append("<li>DOB is Required</li>");
                                            }

                                            /* getting all inputs */

                                            if (!jQuery("#timeAppender").val()) {
                                                errorsDefault = true;
                                                jQuery("#timeAppender").addClass('error');
                                                jQuery('#errors').append("<li>Time is Required</li>");
                                            }
                                            if (!jQuery("#options").val()) {
                                                errorsDefault = true;
                                                jQuery("#options").addClass('error');
                                                jQuery('#errors').append("<li>Reason is Required</li>");
                                            }


                                            if (jQuery.trim(jQuery("#patient_type").val()) == '') {
                                                errorsDefault = true;
                                                jQuery("#patient_type").addClass('error');
                                                jQuery('#errors').append("<li>Please select a patient type</li>");
                                            }
                                            if (!jQuery("#dateAppender").val()) {
                                                errorsDefault = true;
                                                jQuery("#dateAppender").addClass('error');
                                                jQuery('#errors').append("<li>Date is Required</li>");
                                            }
                                            if (!jQuery("#callHelp").is(':checked')) {
                                                errorsDefault = true;
                                                jQuery('#errors').append("<li>You must review the information on when to call 911</li>");
                                            }

                                            if (jQuery("#dateAppender").val() != '') {
                                                var jsDate = new Date();
                                                var currentDateVal = "" + jsDate.getFullYear() + ((jsDate.getMonth().toString().length == 1) ? "0" + jsDate.getMonth() : jsDate.getMonth()) + jsDate.getDate();
                                                var inputVal = jQuery("#dob").val();
                                                var parts = inputVal.split('/');
                                                if (parts.length == 3) {
                                                    /* comparing date */
                                                    var dateFinal = "" + parts[2] + parts[0] + parts[1];
                                                    if (dateFinal > currentDateVal) {
                                                        errorsDefault = true;
                                                        jQuery('#errors').append("<li>Date should be previous </li>");
                                                    } else {

                                                    }
                                                } else {
                                                    errorsDefault = true;
                                                    jQuery('#errors').append("<li>Invalid Date Format is provided</li>");
                                                }
                                            }
                                            if(errorsDefault == true){
                                                var scrollPosition = jQuery(".form-input-group #errors").offset().top - 20;
                                                jQuery('html, body').animate({
                                                    scrollTop: scrollPosition
                                                }, 1000);
                                            }

                                            if (errorsDefault == false) {
                                                jQuery.ajax({
                                                    url: '<?php echo site_url(); ?>' + "/post-clinic-booking-submit-ajax",
                                                    type: 'POST',
                                                    data: form_data,
                                                    processData: false,
                                                    contentType: false,
                                                    success: function (res) {
                                                        jQuery('#loading').addClass('hidden');

                                                        try {
                                                            jsonData = JSON.parse(res);
                                                            if (jsonData.hasOwnProperty('error')) {
                                                                var errorMessage = jsonData.error.replace('400 Bad Request:', '').trim();
                                                                /* looping through all messages */
                                                                if (jsonData.hasOwnProperty('messages')) {
                                                                    if (jQuery('#first_name').val() == '') {
                                                                        jQuery("#first_name").addClass('error');
                                                                        jQuery('#errors').append("<li>First name is Required</li>");
                                                                    }
                                                                    if (!jQuery('#last_name').val()) {
                                                                        jQuery("#last_name").addClass('error');
                                                                        jQuery('#errors').append("<li>Last name is Required</li>");
                                                                    }
                                                                    if (!jQuery('#email').val()) {
                                                                        jQuery("#email").addClass('error');
                                                                        jQuery('#errors').append("<li>Email is Required</li>");
                                                                    }
                                                                    if (!jQuery('#dob').val()) {
                                                                        jQuery("#dob").addClass('error');
                                                                        jQuery('#errors').append("<li>DOB is Required</li>");
                                                                    }
                                                                    jQuery.each(jsonData.messages, function (i, data) {
                                                                        /* getting all inputs */
                                                                        if (data.indexOf('apt_time') >= 0) {
                                                                            jQuery("#timeAppender").addClass('error');
                                                                            jQuery('#errors').append("<li>Time is Required</li>");
                                                                        } else if (data.indexOf('reason_id') >= 0) {
                                                                            jQuery("#options").addClass('error');
                                                                            jQuery('#errors').append("<li>Reason is Required</li>");
                                                                        } else if (data.indexOf('is_new_patient') >= 0) {
                                                                            jQuery("#patient_type").addClass('error');
                                                                            jQuery('#errors').append("<li>Patient type Required</li>");
                                                                        } else {

                                                                        }

                                                                    });
                                                                }
                                                                swal(
                                                                    'Please update your information',
                                                                    errorMessage,
                                                                    'error'
                                                                );
                                                                return false;
                                                            }
                                                            else {
                                                                swal(
                                                                    'Congratulations!',
                                                                    'You have successfully reserved your spot in line.',
                                                                    'success'
                                                                );
                                                                var hospitalID = jsonData.hospital_id;
                                                                var appointmentID = jsonData.id;
                                                                var confirmCode = jsonData.confirmation_code;

                                                                window.setTimeout(function () {
                                                                    var html = ""+
                                                                        '<span class="date-time">Your approximate visit is on ' + date_formatted  + ' at ' + time_formatted.replace(/^0+(?!\.|$)/, '') + '</span>' +
                                                                        '<div class="links">' +
                                                                        '<p class="reschedule">' +
                                                                        '<a target="_blank" href="http://clockwisemd.com/hospitals/' + hospitalID + '/appointments/' + appointmentID + '/reschedule?reservation_code=' + confirmCode + '">Reschedule</a>' +
                                                                        '</p>' +
                                                                        '<p class="cancel">' +
                                                                        '<a target="_blank" href="http://clockwisemd.com/hospitals/' + hospitalID + '/appointments/' + appointmentID + '/cancellation?reservation_code=' + confirmCode + '">Cancel</a>' +
                                                                        '</p>' +
                                                                        '</div>' +
                                                                        "";
                                                                    jQuery('#appointment-result-data').empty().append(html);
                                                                    jQuery('.hide-after-submit').hide();
                                                                    jQuery('.show-after-submit').show();
                                                                    jQuery(window).scrollTop(0);
                                                                }, 2000);
                                                            }
                                                        } catch (e) {
                                                            // is not a valid JSON string
                                                            console.log('error');
                                                            console.log(e);
                                                            console.log(res);
                                                            //console.log(JSON.stringify(JSON.parse(res),null,2));
                                                            jQuery('.errorOptimizer').removeClass('hidden').html(res);
                                                            jQuery('#closespaner').on('click', function () {
                                                                jQuery('.errorOptimizer').empty().addClass('hidden');
                                                            });
                                                        }
                                                        return false;
                                                    }, error: function (error) {
                                                        alert(error);
                                                    }
                                                });
                                            } else {
                                                jQuery('#loading').addClass('hidden');
                                            }
                                        }

                                        jQuery("button.btn-sent").on('click', save_appointment);
                                        /* date picker *//*
                                        jQuery(function () {
                                             jQuery('#dob').datepicker({
                                                 dateFormat: "mm/dd/yy",
                                                 changeYear: true,
                                                 yearRange: "-120:+0",
                                                 maxDate: new Date()
                                             });
                                        });*/

                                        /* auto completer */
                                        //Put our input DOM element into a jQuery Object
                                        var $jqDate = jQuery('input#dob');

                                        //Bind keyup/keydown to the input
                                        $jqDate.bind('keyup', 'keydown', function (e) {

                                            //To accomdate for backspacing, we detect which key was pressed - if backspace, do nothing:
                                            if (e.which !== 8) {/*
                                             var numChars = $jqDate.val().length;
                                             if (numChars === 2 || numChars === 5) {
                                             var thisVal = $jqDate.val();
                                             thisVal += '/';
                                             $jqDate.val(thisVal);
                                             }
                                             */

                                                if (e.keyCode == 8 || e.keyCode == 37 || e.keyCode == 39) {


                                                } else {

                                                    var strDate = $jqDate.val();


                                                    strDate = strDate.replace(/\//g, '');


                                                    str = strDate.substr(0, 2) + '/' + strDate.substr(2, 2) + '/' + strDate.substr(4, 4)

                                                    str = str.replace(/\/\/\//g, '/');
                                                    str = str.replace(/\/\//g, '/');

                                                    if (str.substr((str.length - 1), 1) == '/') str = str.substr(0, (str.length - 1));

                                                    $jqDate.val(str);


                                                }
                                            }
                                        });


                                        /* event  */
                                        Array.prototype.forEach.call(document.body.querySelectorAll("*[data-mask]"), applyDataMask);
                                        function applyDataMask(field) {
                                            var mask = field.dataset.mask.split('');

                                            // For now, this just strips everything that's not a number
                                            function stripMask(maskedData) {
                                                function isDigit(char) {
                                                    return /\d/.test(char);
                                                }

                                                return maskedData.split('').filter(isDigit);
                                            }

                                            // Replace `_` characters with characters from `data`
                                            function applyMask(data) {
                                                return mask.map(function (char) {
                                                    if (char != '_') return char;
                                                    if (data.length == 0) return char;
                                                    return data.shift();
                                                }).join('')
                                            }

                                            function reapplyMask(data) {
                                                return applyMask(stripMask(data));
                                            }

                                            function changed() {
                                                var oldStart = field.selectionStart;
                                                var oldEnd = field.selectionEnd;

                                                field.value = reapplyMask(field.value);

                                                field.selectionStart = oldStart;
                                                field.selectionEnd = oldEnd;
                                            }

                                            field.addEventListener('click', changed)
                                            field.addEventListener('keyup', changed)
                                        }
                                    //]]>
                                    </script>

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
                </div>
            </main><!-- #main -->
        </div><!-- #primary -->
    </div><!-- .wrap -->

<?php get_footer();
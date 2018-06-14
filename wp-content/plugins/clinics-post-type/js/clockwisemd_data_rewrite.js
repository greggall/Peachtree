
if (typeof(hsp_info) != 'undefined') {

    function createCookie(name, value, days) {
        var expires;
        if (! days) {
            days = 30;
        }
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        } else {
            expires = "";
        }
        document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=" + window.location.pathname;
    }
    function readCookie(name) {
        var nameEQ = encodeURIComponent(name) + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
        }
        return null;
    }
    function eraseCookie(name) {
        createCookie(name, "", -1);
    }

    jQuery(document).ready(function() {
        var url = window.location.href;
        if ((! ((url.indexOf("?location_search_query=") !== -1) || (url.indexOf("&location_search_query=") !== -1)))
            && (! ((url.indexOf("?location_search_current=") !== -1) || (url.indexOf("&location_search_current=") !== -1)))
        ) {
            var cookieValue = JSON.parse(readCookie('search_location_address'));
            if (cookieValue !== null) {
                if ((typeof(cookieValue.is_current) != 'undefined') && (cookieValue.is_current === true)) {
                    function clickOnUseMyLocation() {
                        if ((typeof(btnMyLoc) != 'undefined') && (markers.length == hsp_count)) {
                            jQuery('#current-location-btn').click();
                        } else {
                            setTimeout(function () {
                                clickOnUseMyLocation();
                            }, 300);
                        }
                    }

                    clickOnUseMyLocation();
                } else if ((typeof(cookieValue.address) != 'undefined') && (cookieValue.address != '')) {
                    function clickOnSearchLocation() {
                        if ((typeof(geocoder) != 'undefined') && (markers.length == hsp_count)) {
                            jQuery('#address').val(cookieValue.address);
                            jQuery('#address-search-btn').click();
                        } else {
                            setTimeout(function () {
                                clickOnSearchLocation();
                            }, 300);
                        }
                    }

                    clickOnSearchLocation();
                }
            } else {
                function clickOnUseMyLocation() {
                    if ((typeof(btnMyLoc) != 'undefined') && (markers.length == hsp_count)) {
                        jQuery('#current-location-btn').click();
                    } else {
                        setTimeout(function () {
                            clickOnUseMyLocation();
                        }, 300);
                    }
                }

                clickOnUseMyLocation();
            }
        }

    });

    hsp_info.sort(function (a, b) {
        a = a.hospital_name.toLowerCase();
        b = b.hospital_name.toLowerCase();
        return (a < b) ? -1 : (a > b) ? 1 : 0;
    });

    var selected_hospital_id = null;
    var matched_hospitals_to_focus = [];
    function calcRoutes() {
        pathWay = [];
        latlngbounds = map.getBounds();
        route_times = [];
        if ( limitHospitals() ) {
            for (var l = 0; l < hsp_count; l++) {
                getOneRoute(l);
            };
            sortHospitals();
        }
    }

    function resetWindow(num, focused) {
        var newOptions = jQuery.extend(true, {}, boxOptions);
        if (focused) {
            selected_hospital_id = hsp_info[num].id;
            var content = Mustache.to_html(full_template,hsp_info[num], {drive_time: null, drive_distance: null});
            var zI = 100;
            var boxStyle = { opacity: 0.90 };
            newOptions.closeBoxURL = "http://www.google.com/intl/en_us/mapfiles/close.gif";
            var $liElem = jQuery('#list-hospital-' + hsp_info[num].id);
            $liElem.addClass('focused').removeClass('hide');
            var $ulElem = jQuery('#group-hospital-list');
            $ulElem.css({paddingTop: $liElem.outerHeight() + 'px'});
            if ($ulElem.children().index($liElem) >= 3) {
                $ulElem.addClass('has-focused');
            }
            markers[num].setZIndex(100);
            markers[num].setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png');
            markers[num].setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(function () {
                markers[num].setAnimation(null);
            }, 700);
        } else {
            if (hsp_info[num].id == selected_hospital_id) {
                selected_hospital_id = null;
            }
            var content = Mustache.to_html(wait_template,hsp_info[num]);
            var zI = 99;
            var boxStyle = {};
            jQuery('#list-hospital-' + hsp_info[num].id).removeClass('focused');
            jQuery('#group-hospital-list').css({paddingTop: 0}).removeClass('has-focused');
        }
        infoBoxes[num].close();
        newOptions.content = content;
        newOptions.zIndex = zI ;
        newOptions.boxStyle = boxStyle;
        if (! focused) {
            newOptions.boxClass = "hospital-infobox small";
        }
        infoBoxes[num] = new InfoBox(newOptions);
        if (focused) {
            var curNum = num;
            google.maps.event.addListener(infoBoxes[curNum],'closeclick',function(){
                resetMarkers();
                resetWindow(curNum, false);
            });
        }
        infoBoxes[num].open(map, markers[num]);
        prepWindow(num);
    }

    function resetMarker(hsp_index){
        markers[hsp_index] = new google.maps.Marker({
            icon: hsp_info[hsp_index].icon_url,
            map: map,
            position: hspLatLngs[hsp_index],
            title: hsp_info[hsp_index].title,
            zIndex: 99
        });
        var newOptions = jQuery.extend(true, {}, boxOptions);
        newOptions.content = Mustache.to_html(wait_template,hsp_info[hsp_index]);
        newOptions.zIndex = 99;
        newOptions.boxClass = "hospital-infobox small";
        infoBoxes[hsp_index] = new InfoBox(newOptions);
        infoBoxes[hsp_index].open(map, markers[hsp_index]);
        prepWindow(hsp_index);
        google.maps.event.addListener(markers[hsp_index], 'click', function(){ toggleWindows(hsp_info[hsp_index].id);
            resetMarkers();
            resetWindow(hsp_index, true);

        })
    }

    function sortHospitals() {
        if (pathWay.length == hsp_count) {
            pathWay.sort(sortFunction);
            if (pathWay[hsp_count - 1] != undefined) {
            //    focusMap(ids[pathWay[0][4]]);
                var listDiv = jQuery('#group-hospital-list');
                listDiv.empty();
                latlngbounds = map.getBounds();
                latlngbounds = new google.maps.LatLngBounds()
                latlngbounds.extend(patient_latlng);
                for (var i = 0; i < hsp_count; i++) {
                    if (pathWay[i] != undefined) {
                        var curInd = pathWay[i][4];
                        path_index[curInd] = i;
                        var this_time = pathWay[i][0] * 60.0;
                        if (i < 27) { var marker_char = String.fromCharCode(65 + i) }
                        else { var marker_char = ''};
                        var curIcon = "//www.google.com/mapfiles/marker" + marker_char +".png";
                        latlngbounds.extend(markers[i].getPosition());
                        map.fitBounds(latlngbounds);
                        hsp_info[curInd].icon_url = curIcon;

                        hsp_info[curInd].drive_time = "<a class='directions-link' data-no-turbolink='true' onclick=displayWay("+hsp_info[curInd].id+")><strong>Directions</strong></a>";
                        var $currentListElem = jQuery(Mustache.to_html(list_template,hsp_info[curInd]));
                        $currentListElem.on('hover', function (e) {
                            if (! jQuery(this).hasClass('focused')) {
                                var id = jQuery(this).attr('id').replace('list-hospital-', '');
                                markers[id_index[id]].setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png');
                            }
                        }).on('mouseleave', function (e) {
                            if (! jQuery(this).hasClass('focused')) {
                                var id = jQuery(this).attr('id').replace('list-hospital-', '');
                                markers[id_index[id]].setIcon(hsp_info[id_index[id]].icon_url);
                            }
                        });
                        listDiv.append($currentListElem);

                        loadMultipleWaitTimes(curInd);
                        markers[curInd].setIcon(curIcon);
                    };
                };
                if (selected_hospital_id !== null) {
                    var $liElem = jQuery('#list-hospital-' + selected_hospital_id);
                    $liElem.addClass('focused').removeClass('hide');
                    var $ulElem = jQuery('#group-hospital-list');
                    $ulElem.css({paddingTop: $liElem.outerHeight() + 'px'});
                    if ($ulElem.children().index($liElem) >= 3) {
                        $ulElem.addClass('has-focused');
                    }
                } else {
                    jQuery('#group-hospital-list').css({paddingTop: 0}).removeClass('has-focused');
                }
                if (matched_hospitals_to_focus.length) {
                    var bounds_to_focus = new google.maps.LatLngBounds();
                    for (var i = 0; i < matched_hospitals_to_focus.length; i++) {
                        bounds_to_focus.extend(new google.maps.LatLng(matched_hospitals_to_focus[i].coords[0], matched_hospitals_to_focus[i].coords[1]));
                    }
                    map.setCenter(new google.maps.LatLng(currentlat, currentlng));
                    map.fitBounds(bounds_to_focus);
                    jQuery('#map-panel').addClass('current-wait-container-shown');
                } else {
                    jQuery('#map-panel').removeClass('current-wait-container-shown');
                }
            } else {
                setTimeout(function(){sortHospitals()}, 300);
            }
        } else { setTimeout(function(){sortHospitals()}, 300); }
    }

    function addHospitalList() {
        var listDiv = jQuery('#group-hospital-list');
        listDiv.css({paddingTop: 0});
        listDiv.empty();
        for (var i = 0; i < hsp_count; i++) {
            hsp_info[i].drive_time = "<a class='directions-link' data-no-turbolink='true' onclick=displayWay("+hsp_info[i].id+")><strong>Directions</strong></a>";
            var $currentListElem = jQuery(Mustache.to_html(list_template,hsp_info[i]));
            $currentListElem.on('hover', function (e) {
                if (! jQuery(this).hasClass('focused')) {
                    var id = jQuery(this).attr('id').replace('list-hospital-', '');
                    markers[id_index[id]].setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png');
                }
            }).on('mouseleave', function (e) {
                if (! jQuery(this).hasClass('focused')) {
                    var id = jQuery(this).attr('id').replace('list-hospital-', '');
                    markers[id_index[id]].setIcon(hsp_info[id_index[id]].icon_url);
                }
            });
            listDiv.append($currentListElem);

            loadMultipleWaitTimes(i);
        }
    }

    function displayWay(id) {
        var which_hsp = id_index[id];
        var which_path = path_index[which_hsp];
        if (typeof pathWay[which_path] == 'undefined') {
            var getDirectionsUrl = "https://www.google.com/maps/dir/Current+Location/" + encodeURIComponent(hsp_info[which_hsp].search_address) + "/@" + hsp_info[which_hsp].coords[0] + "," + hsp_info[which_hsp].coords[1];
            window.open(getDirectionsUrl, '_blank');
            return;
        }
        scrollToMap();
        var request = {
            origin: pathWay[which_path][3].origin,
            destination: pathWay[which_path][3].destination,
            travelMode: google.maps.TravelMode.DRIVING
        };
        directionsService.route(request, function (result, status) {
            if (status == google.maps.DirectionsStatus.OK) {

                jQuery(divMap).addClass("compressed-map-pane");
                divPanel.style.display = "block";
                divClinic.style.display = "block";
                computeTotalDistance(result);

                setTimeout(function(){
                    jQuery(divClinic).html(Mustache.to_html( full_template, jQuery.extend({}, hsp_info[which_hsp], {drive_time: null, drive_distance: null}) ) );
                    jQuery('[id^="list-hospital-"]').removeClass('focused');
                    jQuery('#list-hospital-' + hsp_info[which_hsp].id).addClass('focused').removeClass('hide');
                    jQuery('#group-hospital-list').css({paddingTop: jQuery('#list-hospital-' + hsp_info[which_hsp].id).outerHeight() + 'px'});
                    for (var i = 0; i < hsp_count; i++) {
                        markers[i].setVisible(false);
                        infoBoxes[i].close();
                    };
                    my_loc_icon.setVisible(false);
                },200);

                for (var i = 0; i < hsp_count; i++) {
                    jQuery("#list-hospital-"+ids[i]).find(".map-tooltip").hide();
                };
            };
        });
        got_directions = true;
    }

    function codeAddress() {
        patient_address = document.getElementById('address').value;
        if (patient_address) {

            got_directions = false;
            reDrawMap();
            jQuery("#address").val(patient_address);

            geocoder.geocode({ 'address': patient_address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    patient_latlng = results[0].geometry.location;
                    currentlat = results[0].geometry.location.lat();
                    currentlng = results[0].geometry.location.lng();
                    my_loc_icon.setOptions({
                        map: map,
                        position: patient_latlng,
                        title: "My Location",
                        icon: "//www.google.com/mapfiles/ms/micons/green-dot.png"
                    });
                    my_loc_icon.setVisible(true);
                    jQuery('#address-search-btn').val("Search Again");
                    jQuery('#clear-address-btn').show();
                    setTimeout(function () {
                        calcRoutes();
                    }, 500);
                } else {
                    alert('Unable to locate address');
                }
            });
            createCookie(
                'search_location_address',
                JSON.stringify({address: patient_address, is_current: false})
            );
        } else { alert('Please enter an address')};
    }

    function findPosition() {
        location_timeout = setTimeout(function(){positionError(null)},10000);
        location_failed = false;
        if (can_block) {
            jQuery.blockUI({
                message: "<h5 class='opensans'><strong>Checking</strong></h5><h5 class='opensans'><strong>Address...</strong></h5>",
                onOverlayClick: jQuery.unblockUI
            });
        } else {
            btnMyLoc.value = "Checking Address..."
        }
        geoPosition.getCurrentPosition(positionSuccess, positionError);
        createCookie(
            'search_location_address',
            JSON.stringify({address: '', is_current: true})
        );
    }

    function prepWindow(k) {
        var hospital_id = hsp_info[k].id;
        google.maps.event.addListener(infoBoxes[k], 'domready', function(){
            var $block = jQuery("#hospital-window-"+hospital_id);
            var boxdiv = $block.parent("div");
            boxdiv.on("click", function(){ if ($block.hasClass('map-window-wait')) { toggleWindows(hospital_id); } });
        });
    }

    function getOneRoute(m) {
        destination_address = {lat: hsp_info[m].coords[0], lng: hsp_info[m].coords[1]};
        var request = {
            origin: patient_latlng,
            destination: destination_address,
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.IMPERIAL
        };

        directionsService.route(request, function (response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                var total_dist = 0;
                var total_time = 0;
                var myroute = response.routes[0];
                for (var i = 0; i < myroute.legs.length; i++) {
                    total_dist += myroute.legs[i].distance.value;
                    total_time += myroute.legs[i].duration.value;
                };
                var display_time = Math.round(total_time / 60.0);
                pathWay.push([total_time/60.0, total_dist / 1000.0, response, request, m]);
                route_times.push(total_time);
                var total_dist_miles = total_dist / 1609.344;
                total_dist_miles = total_dist_miles.toFixed(2);
                hsp_info[m].drive_time = "<a class='directions-link' data-no-turbolink='true' onclick=displayWay("+hsp_info[m].id+")><strong>Directions</strong></a>";
                hsp_info[m].drive_distance = '<span class="drive-distance">'+"("+total_dist_miles+"mi)"+"</span>";
                resetWindow(m, false);
            } else {
                var distanceRequest = {
                    origins: [patient_latlng],
                    destinations: [destination_address],
                    travelMode: google.maps.TravelMode.DRIVING,
                    unitSystem: google.maps.UnitSystem.IMPERIAL
                };
                distanceService.getDistanceMatrix(distanceRequest, function(response, status){
                    if (status == google.maps.DistanceMatrixStatus.OK) {
                        var element = response.rows[0].elements[0];
                        if ((typeof (element.status) != 'undefined') && (element.status == 'NOT_FOUND')) {
                            pathWay.push([99999.99, 99999.99, "No Response", request, m]);
                            hsp_info[m].drive_time = "<a class='directions-link' href='javascript:'><strong>Directions Unknown</strong></a>";
                            hsp_info[m].drive_distance = '';
                            return;
                        }
                        var total_time = element.duration.value;
                        var total_dist = element.distance.value;
                        var display_time = Math.round(total_time / 60.0);
                        pathWay.push([total_time/60.0, total_dist / 1000.0, "distance-only", request, m]);
                        route_times.push(total_time);
                        var total_dist_miles = total_dist / 1609.344;
                        total_dist_miles = total_dist_miles.toFixed(2);
                        hsp_info[m].drive_time =  "<a class='directions-link' data-no-turbolink='true' onclick=displayWay("+hsp_info[m].id+")><strong>Directions</strong></a>";
                        hsp_info[m].drive_distance = '<span class="drive-distance">'+"("+total_dist_miles+"mi)"+"</span>";
                        resetWindow(m, false);
                    } else {
                        pathWay.push([99999.99, 99999.99, "No Response", request, m]);
                        hsp_info[m].drive_time = "<a class='directions-link' href='javascript:'><strong>Directions Unknown</strong></a>";
                        hsp_info[m].drive_distance = '';
                    };
                });
            };
        });
    }

    function limitHospitals(){
        // returns a boolean indicating whether any hospitals were found
        var matching_hsps = [];
        var searchAreaRadius = 80;
        var matching_hsps_to_focus = [];
        for (var i = 0; i < hsp_count; i++) {
            var dist = latLngDist(patient_latlng, hspLatLngs[i]);
            if ( dist < searchAreaRadius ) { matching_hsps_to_focus.push( jQuery.extend(true, {distance: dist}, full_hsp_list[i]) ) }
            matching_hsps.push( jQuery.extend(true, {distance: dist}, full_hsp_list[i]) );
        };
        matching_hsps_to_focus.sort(function (a, b) {
            a = a.distance;
            b = b.distance;
            return (a < b) ? -1 : (a > b) ? 1 : 0;
        });
        if (matching_hsps.length === 0){
            if (can_block) {
                jQuery.blockUI({
                    message: "<h5 class='opensans'><strong>No locations</strong></h5><h5 class='opensans'><strong>within "+searchAreaRadius+" miles</strong></h5>",
                    onOverlayClick: jQuery.unblockUI
                });
                setTimeout( function(){jQuery.unblockUI()}, 2500 );
            };
            if (hsp_count !== full_hsp_count) {
                // reset the map while keeping the last search
                resetFullList();
                resetMarkers();
                initialize();
                my_loc_icon.setOptions({
                    map: map,
                    position: patient_latlng,
                    title: "My Location",
                    icon: "//www.google.com/mapfiles/ms/micons/green-dot.png"
                });
            };
            matched_hospitals_to_focus = [];
            return false
        } else {
            hsp_info = matching_hsps;
            matched_hospitals_to_focus = matching_hsps_to_focus.slice(0, 3);
            resetHspLists();
            return true
        };
    }

    function reDrawMap() {
        matched_hospitals_to_focus = [];
        pathWay = [];
        jQuery('#map-panel').removeClass('current-wait-container-shown');
        resetFullList();
        jQuery('#address').val('');
        jQuery('#address-search-btn').val("Search");
        jQuery('#clear-address-btn').hide();
        resetMarkers();
        initialize();
        eraseCookie('search_location_address');
    }

    function loadMultipleWaitTimes(hps_info_index) {
        if (jQuery('#list-hospital-' + hsp_info[hps_info_index].hospital_id).length) {
            var updateTimes = function (hps_info_index) {
                jQuery('script[src*="'+'www.clockwisemd.com/hospitals/'+hsp_info[hps_info_index].hospital_id+'/available_times.json'+'"]').remove();
                Clockwise.AvailableTimes(hsp_info[hps_info_index].hospital_id, 0, 'json', 0);
                jQuery('body').on('clockwise_times_loaded', function (e, id, result) {
                    if (hsp_info[hps_info_index].hospital_id == id) {
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
                            jQuery('script[src*="'+'www.clockwisemd.com/hospitals/'+id+'/available_times.json'+'"]').remove();
                            Clockwise.AvailableTimes(id, result + 1, 'json', 0);
                        } else {
                            var timesToPrint = [];
                            var timesToPrintQty = 5;
                            var counter = 0;
                            var buttonsHtml = '';
                            var $currentListItem = jQuery('#list-hospital-' + id);
                            var baseUrl = (typeof (websiteBaseUrl) != 'undefined') ? websiteBaseUrl : '';
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
                            buttonsHtml +=
                                '<a class="check-in-button" target="_blank" href="' + baseUrl + '/get-clinic-book-page-by-api-id?id=' + id + '">' +
                                '<div class="btn btn-primary">Check In</div>' +
                                '</a>';
                            $currentListItem.find('.get-in-line-buttons').html(
                                buttonsHtml
                            );
                        }
                    }
                });
            };
            updateTimes(hps_info_index);
            var interval = setInterval(function () {
                updateTimes(hps_info_index);
            }, 30000);
        }

    }

}
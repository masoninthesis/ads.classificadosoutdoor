<div class="sidebar_map clearfix">
    <?php
    global $post, $wp_query;
    $post = $wp_query->post;
    if (is_single()) {
        $address = get_post_meta($post->ID, 'cc_address', true);
        $address_latitude = get_post_meta($post->ID, 'cc_latitude', true);
        $address_longitude = get_post_meta($post->ID, 'cc_longitude', true);
        if (empty($address_latitude) && empty($address_longitude)) {
            $addr = str_replace("&#44;", ", ", $address);
            $url = "http://maps.googleapis.com/maps/api/geocode/xml?address=" . $address . "&sensor=false";
            $getAddress = simplexml_load_file($url);
            $address_latitude = $getAddress->result->geometry->location->lat;
            $address_longitude = $getAddress->result->geometry->location->lng;
        } else {
            $address_latitude = get_post_meta($post->ID, 'cc_latitude', true);
            $address_longitude = get_post_meta($post->ID, 'cc_longitude', true);
        }
        
        $map_type = 'ROADMAP';
        if ($map_type == 'Default Map') {
            $map_type = 'ROADMAP';
        } elseif ($map_type == 'Satellite Map') {
            $map_type = 'SATELLITE';
        } elseif ($map_type == 'Hybrid Map') {
            $map_type = 'TERRAIN';
        } else {
            $map_type = 'ROADMAP';
        }
        if (get_post_meta($post->ID, 'zooming_factor', true)) {
            $scale = get_post_meta($post->ID, 'zooming_factor', true);
        } else {
            $scale = 14;
        }

        if ($address_longitude && $address_latitude) {
            ?>
            <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
            <script type="text/javascript">
                /* <![CDATA[ */

                var basicsetting = {
                    draggable: true
                };
                var directionsDisplay = new google.maps.DirectionsRenderer(basicsetting);
                var directionsService = new google.maps.DirectionsService();
                var map;
                             
                var latLng = new google.maps.LatLng(<?php echo $address_latitude; ?>, <?php echo $address_longitude; ?>);
                             
                function initialize() {
                             
                    var myOptions = {
                        zoom: <?php echo $scale; ?>,
                        mapTypeId: google.maps.MapTypeId.<?php echo $map_type; ?>,
                        zoomControl: true,
                        center: latLng	  
                    };
                    map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
                    directionsDisplay.setMap(map);
                    directionsDisplay.setPanel(document.getElementById("directionsPanel"));
                             
                    var image = '<?php echo $catinfo; ?>';
                    var myLatLng = new google.maps.LatLng(<?php echo $address_latitude; ?>, <?php echo $address_longitude; ?>);
                    var Marker = new google.maps.Marker({
                        position: latLng,
                        map: map,
                        icon: image
                    });
                    var content = '<?php echo $tooltip_message; ?>';
                    infowindow = new google.maps.InfoWindow({
                        content: content
                    });
                            	
                    google.maps.event.addListener(Marker, 'click', function() {
                        infowindow.open(map,Marker);
                    });
                    google.maps.event.addListener(directionsDisplay, 'directions_changed', function() {
                               
                    });
                }
                              
                function getSelectedTravelMode() {
                    var travelvalue =  document.getElementById('travel-mode-input').value;
                            	
                    if (travelvalue == 'driving') {
                        travelvalue = google.maps.DirectionsTravelMode.DRIVING;
                    } else if (travelvalue == 'bicycling') {
                        travelvalue = google.maps.DirectionsTravelMode.BICYCLING;
                    } else if (travelvalue == 'walking') {
                        travelvalue = google.maps.DirectionsTravelMode.WALKING;
                    } else {
                        alert('Unsupported travel mode.');
                    }
                    return travelvalue;
                }
                              
                function calcRoute() {
                    var destination_val = document.getElementById('fromAddress').value;
                             
                    var request = {
                        origin: destination_val,
                        destination: "<?php echo $address_latitude; ?>, <?php echo $address_longitude; ?>",
                        travelMode: google.maps.DirectionsTravelMode.DRIVING
                    };
                    directionsService.route(request, function(response, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            directionsDisplay.setDirections(response);
                        }else {alert('<?php _e('Address not found for:', THEME_SLUG); ?>'+ destination_val);}
                    });
                }
                             
                google.maps.event.addDomListener(window, 'load', initialize);

                /* ]]> */
            </script>
            <div class="single-map" id="map-canvas" style="height:250px;"></div>        
            <?php
        }
    }
    ?>
</div>
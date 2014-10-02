<?php
$maptype = 'ROADMAP';
$cc_latitude = get_post_meta($post->ID, 'cc_latitude', true);
$cc_longitude = get_post_meta($post->ID, 'cc_longitude', true);
?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>
<script type="text/javascript"> 
<?php
if (is_admin()) {
    ?>
            jQuery(document).ready(function(){
               jQuery('.b_submit').click(function(){
                var street = jQuery('#cc_street').val();
                var city = jQuery('#cc_city').val();
                var zipcode = jQuery('#cc_zipcode').val();  
                var state = jQuery('#cc_state').val();
                var country = jQuery('#cc_country').val();
                var address = street + ' ' + city + ' ' + zipcode + ' ' + state + ' ' + country; 
                jQuery('#cc_address').val(address);
                geocode();initialize();
            });       
            });  
<?php
}
if (!is_admin()) {
    ?>
        jQuery(document).ready(function(){
            jQuery('.b_submit').click(function(){
                var street = jQuery('#cc_street').val();
                var city = jQuery('#cc_city').val();
                var zipcode = jQuery('#cc_zipcode').val();  
                var state = jQuery('#cc_state').val();
                var country = jQuery('#cc_country').val();
                var address = street + ' ' + city + ' ' + zipcode + ' ' + state + ' ' + country; 
                jQuery('#cc_address').val(address);
                geocode();initialize();
            });
        });
<?php } ?>
        /* <![CDATA[ */
        var map;
        var latlng;
        var geocoder;
        var address;
        var lat;
        var lng;
        var centerChangedLast;
        var reverseGeocodedLast;
        var currentReverseGeocodeResponse;
        
<?php
if (stripslashes($cc_latitude) && stripslashes($cc_longitude)) {
    ?>
         var   map_latitude = '<?php echo $cc_latitude; ?>';
         var   map_longitude = '<?php echo $cc_longitude; ?>';
    <?php
}else{
    ?>
        var map_latitude = '38.9826072';
        var map_longitude = '-76.48395949999997';
        <?php
}
?>
    var map_zooming='13';
    if(map_latitude=='')
    {
        var map_latitude = 34;	
    }
    if(map_longitude=='')
    {
        var map_longitude = 0;	
    }
    if(map_latitude!='' && map_longitude!='' && map_zooming!='')
    {
        var map_zooming = 13;
    }else if(map_zooming!='')
    {
        var map_zooming = 3;	
    }
    function initialize() {
        var latlng = new google.maps.LatLng(map_latitude,map_longitude);
        var myOptions = {
            zoom: map_zooming,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP    };
        map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
        geocoder = new google.maps.Geocoder();
        google.maps.event.addListener(map, 'zoom_changed', function() {
            //document.getElementById("zooming_factor").value = map.getZoom();
			
        });
        setupEvents();
        centerChanged();
    }
    function setupEvents() {
        reverseGeocodedLast = new Date();
        centerChangedLast = new Date();
	
        setInterval(function() {
            if((new Date()).getSeconds() - centerChangedLast.getSeconds() > 1) {
                if(reverseGeocodedLast.getTime() < centerChangedLast.getTime())
                    reverseGeocode();
            }
        }, 1000);
        google.maps.event.addListener(map, 'zoom_changed', function() {
            document.getElementById("zooming_factor").value = map.getZoom();
        });
    }
    function getCenterLatLngText() {
        return '(' + map.getCenter().lat() +', '+ map.getCenter().lng() +')';
    }
    function centerChanged() {
        centerChangedLast = new Date();
        var latlng = getCenterLatLngText();
        document.getElementById('latlng').innerHTML = latlng;
        document.getElementById('cc_address').value = '';
        currentReverseGeocodeResponse = null;
    }
    function reverseGeocode() {
        reverseGeocodedLast = new Date();
        geocoder.geocode({latLng:map.getCenter()},reverseGeocodeResult);
    }
    function reverseGeocodeResult(results, status) {
        currentReverseGeocodeResponse = results;
        if(status == 'OK') {
            if(results.length == 0) {
                document.getElementById('cc_address').value = 'None';
            } else {
                document.getElementById('cc_address').value = results[0].formatted_address;
            }
        } else {
            document.getElementById('cc_address').value = 'Error';
        }
    }
    function geocode() {
        var address = document.getElementById("cc_address").value;
    
		
        if(address) {
            var address = document.getElementById("cc_address").value;
            geocoder.geocode( { 'address': address}, geocodeResult);
        }
    }
    function geocodeResult(results, status) {
        map.setCenter(results[0].geometry.location);
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                position: results[0].geometry.location,
                draggable: true,
                map: map
            });
            addMarkerAtCenter(marker);
	  
        } else {
            alert("Geocode was not successful for the following reason: " + status);
        }
	
    }
    function addMarkerAtCenter(marker) {
	
	
        updateMarkerAddress('Dragging...');
        updateMarkerPosition(marker.getPosition());
        geocodePosition(marker.getPosition());
        google.maps.event.addListener(marker, 'dragstart', function() {
            updateMarkerAddress('Dragging...');
        });
	
        google.maps.event.addListener(marker, 'drag', function() {
            updateMarkerPosition(marker.getPosition());
        });
	
        google.maps.event.addListener(marker, 'dragend', function() {
            geocodePosition(marker.getPosition());
        });
        var text = 'Lat/Lng: ' + getCenterLatLngText();
        if(currentReverseGeocodeResponse) {
            var addr = '';
            if(currentReverseGeocodeResponse.size == 0) {
                addr = 'None';
            } else {
                addr = currentReverseGeocodeResponse[0].formatted_address;
            }
            text = text + '<br>' + 'address: <br>' + addr;
        }
        var infowindow = new google.maps.InfoWindow({ content: text });
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map,marker);
        });
    }
  
    function updateMarkerAddress(str)
    {
        //document.getElementById('geo_address').value = str;
    }
   
    function updateMarkerStatus(str)
    {
        document.getElementById('markerStatus').innerHTML = str;
    }
   
    function updateMarkerPosition(latLng)
    {
        document.getElementById('cc_latitude').value = latLng.lat();
        document.getElementById('cc_longitude').value = latLng.lng();
    }
 
    var geocoder = new google.maps.Geocoder();
    function geocodePosition(pos) {
        geocoder.geocode({
            latLng: pos
        }, function(responses) {
            if (responses && responses.length > 0) {
                updateMarkerAddress(responses[0].formatted_address);
            } else {
                updateMarkerAddress('Cannot determine address at this location.');
            }
        });
    }
    function changeMap()
    {
        var newlatlng = document.getElementById('cc_latitude').value;
        var newlong = document.getElementById('cc_longitude').value;
        var latlng = new google.maps.LatLng(newlatlng,newlong);
        var map = new google.maps.Map(document.getElementById('map_canvas'), {
            zoom: map_zooming,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP	  });
	
        var marker = new google.maps.Marker({
            position: latlng,
            title: 'Point A',
            map: map,
            draggable: true
        });
		
        updateMarkerAddress('Dragging...');
        updateMarkerPosition(marker.getPosition());
        geocodePosition(marker.getPosition());
        google.maps.event.addListener(marker, 'dragstart', function() {
            updateMarkerAddress('Dragging...');
        });
	
        google.maps.event.addListener(marker, 'drag', function() {
            updateMarkerStatus('Dragging...');
            updateMarkerPosition(marker.getPosition());
        });
	
        google.maps.event.addListener(marker, 'dragend', function() {
            updateMarkerStatus('Drag ended');
            geocodePosition(marker.getPosition());
        });
	
    }
	
    google.maps.event.addDomListener(window, 'load', initialize);
    google.maps.event.addDomListener(window, 'load', geocode);
    /* ]]> */
</script>
<input type="button" style="width:150px;" class="b_submit" value="<?php echo SET_MAP; ?>" onclick="geocode();initialize();" />
<div id="map_canvas" style="height:350px; margin-top: 10px; position:relative; width:410px;border:1px solid #4d4d4d;"  class="form_row clearfix"></div>
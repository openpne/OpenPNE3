<?php use_helper('Javascript') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<?php $apiKey = !empty($op_config['google_maps_api_key']) ? '&amp;'.$op_config['google_maps_api_key'] : '' ?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false<?php echo $apiKey ?>"></script>
<script type="text/javascript">
//<![CDATA[

// parse request parameters
var request = {
  x: "", y: "", z: "", q: ""
};
var params = window.location.search.substr(1).split('&');
for (var i = 0; i < params.length; i++) {
  var parts = params[i].split('=');

  var n = parts[0];
  var v = decodeURIComponent(parts[1]);
  if ("z" == n)
  {
    v = parseInt(v);
  }
  request[n] = v;
}

google.maps.event.addDomListener(window, 'load', function() {
  'use strict';

  var mapCenter = new google.maps.LatLng(request.x, request.y)
  var mapType = google.maps.MapTypeId.ROADMAP

  switch (request.t) {
    case 'k':
      mapType = google.maps.MapTypeId.SATELLITE
      break
    case 'h':
      mapType = google.maps.MapTypeId.HYBRID
      break
  }

  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: request.z,
    center: mapCenter,
    mapTypeId: mapType
  })

  if ('' === request.q) {
    var marker = new google.maps.Marker({
      map: map,
      position: mapCenter
    })
  }
  else {
    // Geocoding
    var geocoder = new google.maps.Geocoder()
    geocoder.geocode({'address': request.q}, function(results, status) {
      if (status !== google.maps.GeocoderStatus.OK) {
        alert('ジオコーディングに失敗しました: ' + status)
        return
      }

      var resultPos = results[0].geometry.location
      map.setCenter(resultPos)
      var marker = new google.maps.Marker({
        map: map,
        position: resultPos
      })
    })
  }
})

//]]>
</script>
</head>
<body id="page_googlemaps_index" class="<?php echo opToolkit::isSecurePage() ? 'secure_page' : 'insecure_page' ?>">
<div id="map" style="width: 300px; height: 320px"></div>
</body>
</html>

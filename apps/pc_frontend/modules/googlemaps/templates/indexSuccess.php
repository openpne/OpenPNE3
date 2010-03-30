<?php use_helper('Javascript') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<title><?php echo ($op_config['sns_title']) ? $op_config['sns_title'] : $op_config['sns_name'] ?></title>
<?php echo $op_config->get('pc_html_head') ?>
<?php if (isset($op_config['google_AJAX_search_api_key']) && isset($op_config['google_maps_api_key'])): ?>
<?php use_javascript('http://www.google.co.jp/uds/api?file=uds.js&v=1.0&key='.$op_config['google_AJAX_search_api_key']) ?>
<?php use_javascript('http://maps.google.co.jp/maps?file=api&v=2.x&key='.$op_config['google_maps_api_key']) ?>
<?php
$googlemaps_script = <<<EOM
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
var MapType = %s;  // It is not user-inputed values

var gls;
var gMap;
function OnLocalSearch() {
    if (!gls.results) return;
    var first = gls.results[0];
    var point = new GLatLng(parseFloat(first.lat), parseFloat(first.lng));
    var zoom = request.z;
    gMap.addControl(new GSmallMapControl());
    gMap.addControl(new GMapTypeControl());
    gMap.setMapType(MapType);
    gMap.setCenter(point, zoom);
    var marker = new GMarker(point);
    gMap.addOverlay(marker);
    geocoder = new GClientGeocoder();
}
function load() {
    if (GBrowserIsCompatible()) {
        if ((request.x == 0) && (request.y == 0)){
            gMap = new GMap2(document.getElementById('map'));
            gMap.addControl(new GSmallMapControl());
            gMap.addControl(new GMapTypeControl());
            gMap.setCenter(new GLatLng(0, 0));
            gls = new GlocalSearch();
            gls.setCenterPoint(gMap);
            gls.setSearchCompleteCallback(null, OnLocalSearch);
            var q = request.q;
            gls.execute(q);
        } else {
            var point = new GLatLng(request.x, request.y);
            var zoom = request.z;
            gMap = new GMap2(document.getElementById('map'));
            gMap.addControl(new GSmallMapControl());
            gMap.addControl(new GMapTypeControl());
            gMap.setCenter(point, zoom);
            gMap.setMapType(MapType);
            var marker = new GMarker(point);
            gMap.addOverlay(marker);
            geocoder = new GClientGeocoder();
        }
    }
}
EOM;
echo javascript_tag(sprintf($googlemaps_script, $mapType)); ?>
<?php endif; ?>
<?php include_stylesheets() ?>
<?php include_javascripts() ?>
</head>
<body onload="load()" onunload="GUnload()" id="page_googlemaps_index" class="<?php echo opToolkit::isSecurePage() ? 'secure_page' : 'insecure_page' ?>">
<div id="map" style="width: 300px; height: 320px"></div>
</body>
</html>

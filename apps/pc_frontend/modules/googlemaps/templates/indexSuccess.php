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
var gls;
var gMap;
function OnLocalSearch() {
    if (!gls.results) return;
    var first = gls.results[0];
    var point = new GLatLng(parseFloat(first.lat), parseFloat(first.lng));
    var zoom = (%s);
    gMap.addControl(new GSmallMapControl());
    gMap.addControl(new GMapTypeControl());
    gMap.setMapType((%s));
    gMap.setCenter(point, zoom);
    var marker = new GMarker(point);
    gMap.addOverlay(marker);
    geocoder = new GClientGeocoder();
}
function load() {
    if (GBrowserIsCompatible()) {
        if (((%s) == 0) && ((%s) == 0)){
            gMap = new GMap2(document.getElementById('map'));
            gMap.addControl(new GSmallMapControl());
            gMap.addControl(new GMapTypeControl());
            gMap.setCenter(new GLatLng(0, 0));
            gls = new GlocalSearch();
            gls.setCenterPoint(gMap);
            gls.setSearchCompleteCallback(null, OnLocalSearch);
            var q = '(%s)';
            gls.execute(q);
        } else {
            var point = new GLatLng((%s), (%s));
            var zoom = (%s);
            gMap = new GMap2(document.getElementById('map'));
            gMap.addControl(new GSmallMapControl());
            gMap.addControl(new GMapTypeControl());
            gMap.setCenter(point, zoom);
            gMap.setMapType((%s));
            var marker = new GMarker(point);
            gMap.addOverlay(marker);
            geocoder = new GClientGeocoder();
        }
    }
}
EOM;
echo javascript_tag(sprintf($googlemaps_script, $z, $mapType, $x, $y, $q, $x, $y, $z, $mapType)); ?>
<?php endif; ?>
<?php include_stylesheets() ?>
<?php include_javascripts() ?>
</head>
<body onload="load()" onunload="GUnload()" id="page_googlemaps_index" class="<?php echo opToolkit::isSecurePage() ? 'secure_page' : 'insecure_page' ?>">
<div id="map" style="width: 300px; height: 320px"></div>
</body>
</html>

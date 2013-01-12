<?php use_helper('Javascript') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<title><?php echo ($op_config['sns_title']) ? $op_config['sns_title'] : $op_config['sns_name'] ?></title>
<?php echo $op_config->get('pc_html_head') ?>
<?php include_stylesheets() ?>
<?php include_javascripts() ?>
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
function load()
{
  var latlng = new google.maps.LatLng(request.x, request.y);
  var mapOptions = {
    zoom: request.z,
    center: latlng,
    mapTypeId: MapType,
  };
  var map = new google.maps.Map(document.getElementById('map'), mapOptions);
}
EOM;
echo javascript_tag(sprintf($googlemaps_script, $mapType)); ?>
</head>
<body onload="load()" id="page_googlemaps_index" class="<?php echo opToolkit::isSecurePage() ? 'secure_page' : 'insecure_page' ?>">
<div id="map" style="width: 300px; height: 320px"></div>
</body>
</html>

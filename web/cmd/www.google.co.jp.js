function url2cmd(url, googlemapsUrl) {
  var www_google_co_jp_maps = url.match(/^https?:\/\/www\.google\.co\.jp\/maps[\?\/](.+)/);

  if (www_google_co_jp_maps) {
     var id = RegExp.$1;
     main(id, googlemapsUrl);
    } else {
       pne_url2a(url);
    }
}

function main(id, googlemapsUrl) {
  var param = { lon: 0, lat: 0, z: 15, t: '', q: '' };
  var result = id.match(/(?:^|\/)@(-?[0-9\.]+),(-?[0-9\.]+),([0-9\.]+m|[0-9\.]+z)(\/data=!3m1!1e3)?/);

  if (result) {
    param.lon = result[1];
    param.lat = result[2];
    param.z   = result[3];

    if (param.z.indexOf('m') !== -1) {
      param.z = 15;
      param.t = 'k';
    }
  } else {
    id.replace('&amp;', '&');
    var query = id.split('&');
    for(i = 0; i < query.length; i++) {
      var pair = query[i].split('=');
      if (pair.length !== 2) {
        continue;
      }
      var key = pair[0];
      var value = pair[1];

      if (param[key] !== undefined) {
        param[key] = value;
      } else if ('ll' === key) {
        param.lon = value.split(',')[0];
        param.lat = value.split(',')[1];
      }
    }
  }

  var html = '<iframe marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no" bordercolor="#000000"'
           + 'src="' + googlemapsUrl
           + '?x=' + param.lon
           + '&y=' + param.lat
           + '&z=' + param.z
           + '&t=' + param.t
           + '&q=' + param.q
           + '" name="sample" height="350">この部分はインラインフレームを使用しています。</iframe>';

  document.write(html);
}

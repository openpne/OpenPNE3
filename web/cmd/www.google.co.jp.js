function url2cmd(url, googlemapsUrl) {
  var www_google_co_jp_maps = url.match(/^http:\/\/www\.google\.co\.jp\/maps\?(.+)/);

  if (www_google_co_jp_maps) {
     var id = RegExp.$1;
     main(id, googlemapsUrl);
    } else {
       pne_url2a(url);
    }
}

function main(id, googlemapsUrl) {
    var cmd = id.split("&amp;");
    var param = new Array();
    param["z"] = "15";
    param["ll"] = "0,0";
    for(i=0; i<cmd.length; i++) {
       var work = cmd[i].split("=");
       if( work.length == 2 ) {
         param[work[0]] = work[1];
       }
    }
    var ll = param["ll"].split(",");
    var z = param["z"];
    var t = param["t"];
    var q = param["q"];

    var html = ''
    + '<iframe marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no" bordercolor="#000000" src="'+googlemapsUrl+'?x='+ll[0]+'&y='+ll[1]+'&z='+z+'&t='+t+'&q='+q+'" name="sample" height="350">'
    + 'この部分はインラインフレームを使用しています。'
    + '</iframe>';

    document.write(html);
}

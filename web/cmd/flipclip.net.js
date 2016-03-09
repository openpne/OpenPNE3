//MASTER:flipclip.net.js SLAVE:www.flipclip.net.js
function url2cmd(url) {
    if (!url.match(/^http:\/\/(?:www\.|)flipclip\.net\/clips\/[a-zA-Z0-9_\-]+\/([a-zA-Z0-9_\-]+)\/?$/)) {
        pne_url2a(url);
        return;
    }
    var clipid = RegExp.$1;
    main(clipid);
}

function main(clipid) {
    if (!clipid.match(/^[a-zA-Z0-9_\-]+$/)) {
        return;
    }

    document.writeln('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="229" height="163" align="middle">');
    document.writeln('<param name="allowScriptAccess" value="sameDomain" />');
    document.writeln('<param name="movie" value="http://www.flipclip.net/swf/player/15.swf?c='+clipid +'" />');
    document.writeln('<param name="quality" value="high" />');
    document.writeln('<param name="wmode" value="transparent" />');
    document.writeln('<param name="menu" value="false" />');
    document.writeln('<param name="flashvars" value="webServiceUrl=http://www.flipclip.net/amf/player&serviceName=FlipClip::AMF::Player&clipKey='+ clipid +'&uid=m3loves55&fid='+clipid+'&flv_url=http://www.flipclip.net/videos/m3loves55/'+clipid+'.flv" />');
    document.writeln('<embed src="http://www.flipclip.net/swf/player/15.swf?c='+clipid+'" quality="high" bgcolor="#ffffff" wmode="transparent" width="229" height="163" align="middle"flashvars="webServiceUrl=http://www.flipclip.net/amf/player&serviceName=FlipClip::AMF::Player&clipKey=' +clipid+ '&uid=m3loves55&fid=' +clipid+ '&flv_url=http://www.flipclip.net/videos/m3loves55/' +clipid+ '.flv" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>');
}

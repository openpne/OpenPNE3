//MASTER:flipclip.net.js SLAVE:www.flipclip.net.js
function url2cmd(url) {
    if (!url.match(/^http:\/\/(?:www\.|)flipclip\.net\/clips\/([a-zA-Z0-9_\-]+)\/([a-zA-Z0-9_\-]+)\/?$/)) {
        pne_url2a(url);
        return;
    }
    var userid = RegExp.$1
    var clipid = RegExp.$2;
    main(userid,clipid);
}

function main(userid, clipid) {
    if (!clipid.match(/^[a-zA-Z0-9_\-]+$/) || !userid.match(/^[a-zA-Z0-9_\-]+$/)) {
        return;
    }

    document.writeln('<object');
    document.writeln('classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"');
    document.writeln('codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"');
    document.writeln('width="400"');
    document.writeln('height="300"');
    document.writeln('id="PlayerCore"');
    document.writeln('align="middle">');
    document.writeln('<param name="allowScriptAccess" value="sameDomain" />');
    document.writeln('<param name="movie" value="http://www.flipclip.net/static/swf/frames/CompactXLarge.swf" />');
    document.writeln('<param name="quality" value="high" />');
    document.writeln('<param name="scale" value="noscale" />');
    document.writeln('<param name="salign" value="lt" />');
    document.writeln('<param name="wmode" value="transparent" />');
    document.writeln('<param name="menu" value="false" />');
    document.writeln('<param name="bgcolor" value="#ffffff" />');
    document.writeln('<param name="flashvars" value="coreURL=http://www.flipclip.net/static/swf/PlayerCore3/core.swf&seqURL=http://www.flipclip.net/clips/'+userid+'/'+clipid+'/playinfo/400x300&modPath=http://www.flipclip.net/static/swf/PlayerCore3/module/&autoPlay=false" />');
    document.writeln('<embed');
    document.writeln('src="http://www.flipclip.net/static/swf/frames/CompactXLarge.swf"');
    document.writeln('quality="high"');
    document.writeln('scale="noscale"');
    document.writeln('salign="lt"');
    document.writeln('wmode="transparent"');
    document.writeln('menu="false"');
    document.writeln('bgcolor="#ffffff"');
    document.writeln('width="400"');
    document.writeln('height="300"');
    document.writeln('name="PlayerCore"');
    document.writeln('align="middle"');
    document.writeln('allowScriptAccess="sameDomain"');
    document.writeln('type="application/x-shockwave-flash"');
    document.writeln('pluginspage="http://www.macromedia.com/go/getflashplayer"');
    document.writeln('flashvars="coreURL=http://www.flipclip.net/static/swf/PlayerCore3/core.swf&seqURL=http://www.flipclip.net/clips/'+userid+'/'+clipid+'/playinfo/400x300?modPath=http://www.flipclip.net/static/swf/PlayerCore3/module/&autoPlay=false" />');
    document.writeln('</object>');

}

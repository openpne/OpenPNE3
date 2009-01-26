//MASTER:wajju.jp.js SLAVE:www.wajju.jp.js
function url2cmd(url) {
    if (!url.match(/^http:\/\/(?:www\.|)wajju\.jp\/clips\/([a-zA-Z0-9_\-]+)\/([a-zA-Z0-9_\-]+)\/?$/)) {
        pne_url2a(url);
        return;
    }
    var clipid1 = RegExp.$1;
    var clipid2 = RegExp.$2;
    main(clipid1, clipid2);
}

function main(clipid1, clipid2) {
    if (!clipid1.match(/^[a-zA-Z0-9_\-]+$/)) {
        return;
    }
    if (!clipid2.match(/^[a-zA-Z0-9_\-]+$/)) {
        return;
    }

    var seqURL = 'http://www.wajju.jp/clips/'+clipid1+'/'+clipid2;

    document.writeln('');
    document.writeln('<object');
    document.writeln('      classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"');
    document.writeln('      codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"');
    document.writeln('      width="400"');
    document.writeln('      height="300"');
    document.writeln('      id="PlayerCore"');
    document.writeln('      align="middle">');
    document.writeln('<param name="allowScriptAccess" value="sameDomain" />');
    document.writeln('<param name="movie" value="http://www.wajju.jp/static/swf/plainSkin_400x300_wajju.swf" />');
    document.writeln('<param name="quality" value="high" />');
    document.writeln('<param name="scale" value="noscale" />');
    document.writeln('<param name="salign" value="lt" />');
    document.writeln('<param name="wmode" value="transparent" />');
    document.writeln('<param name="menu" value="false" />');
    document.writeln('<param name="bgcolor" value="#ffffff" />');
    document.writeln('<param name="FlashVars" value="coreURL=http://www.wajju.jp/static/swf/core.swf&seqURL=' + seqURL + '/playinfo&modPath=http://www.wajju.jp/static/swf/modules/&autoPlay=false" />');
    document.writeln('<embed');
    document.writeln('      src="http://www.wajju.jp/static/swf/plainSkin_400x300_wajju.swf"');
    document.writeln('      quality="high"');
    document.writeln('      scale="noscale"');
    document.writeln('      salign="lt"');
    document.writeln('      wmode="transparent"');
    document.writeln('      menu="false"');
    document.writeln('      bgcolor="#ffffff"');
    document.writeln('      width="400"');
    document.writeln('      height="300"');
    document.writeln('      name="PlayerCore"');
    document.writeln('      align="middle"');
    document.writeln('      allowScriptAccess="sameDomain"');
    document.writeln('      type="application/x-shockwave-flash"');
    document.writeln('      pluginspage="http://www.macromedia.com/go/getflashplayer"');
    document.writeln('      FlashVars="coreURL=http://www.wajju.jp/static/swf/core.swf&seqURL=' + seqURL + '/playinfo&modPath=http://www.wajju.jp/static/swf/modules/&autoPlay=false" />');
    document.writeln('</object>');
}

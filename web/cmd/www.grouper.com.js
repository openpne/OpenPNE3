//MASTER:grouper.com.js SLAVE:www.grouper.com.js
function url2cmd(url) {
    if (!url.match(/^http:\/\/(?:www\.|)grouper\.com\/video\/MediaDetails\.aspx\?id=([0-9]+)(.*)$/)) {
        pne_url2a(url);
        return;
    }
    var id = RegExp.$1;
    main(id);
}

function main(id) {
    if (!id.match(/^[0-9]+$/)) {
        return;
    }
    document.writeln('<embed allowScriptAccess="never" src="http://grouper.com/mtg/mtgPlayer.swf?v=1.3" width="425" height="325" quality="high" scale="noScale" FlashVars="vurl=http%3a%2f%2fgrouper.com%2frss%2fflv.ashx%3fid%3d' + id + '%26rf%3d-1&vfver=8&ap=1&extid=-1" wmode="window" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash"> </embed>');
}

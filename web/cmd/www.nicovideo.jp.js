function url2cmd(url) {
    if (!url.match(/^http:\/\/www\.nicovideo\.jp\/watch\/([a-z0-9]+)$/)) {
        pne_url2a(url);
        return false;
    }
    var vid = RegExp.$1;
    var html = '<div style="width:318px; border:solid 1px #CCC;">'
        + '<iframe src="http://www.nicovideo.jp/thumb/' + vid + '" width="100%"'
        + ' height="198" scrolling="no" border="0" frameborder="0">'
        + '<p style="font-size:12px; padding:4px;">iframe対応ブラウザでご覧下さい。</p>'
        + '</iframe></div>';
    document.write(html);
}

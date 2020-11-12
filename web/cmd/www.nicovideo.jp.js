function url2cmd(url) {
    if (!url.match(/^https?:\/\/www\.nicovideo\.jp\/watch\/([a-z0-9]+)$/)) {
        pne_url2a(url);
        return false;
    }
    var vid = RegExp.$1;
    var html = '<iframe id="iframe1" src="https://ext.nicovideo.jp/thumb/' + vid + '" width="350"'
        + ' height="230" scrolling="no" style="border: solid 1px #ccc;" frameborder="0">'
        + '<a href="' + url + '">' + url + '</a>'
        + '</iframe>';
    document.write(html);
}

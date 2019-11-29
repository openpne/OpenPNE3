function url2cmd(url) {
    if (!url.match(/^https?:\/\/www\.nicovideo\.jp\/watch\/([a-z0-9]+)$/)) {
        pne_url2a(url);
        return false;
    }
    var vid = RegExp.$1;
    var html = '<iframe src="https://ext.nicovideo.jp/thumb/' + vid + '" width="100%"'
        + ' height="198" scrolling="no" style="border:solid 1px #ccc;" frameborder="0">'
        + '<a href="' + url + '">' + url + '</a>'
        + '</iframe>';
    document.write(html);
}

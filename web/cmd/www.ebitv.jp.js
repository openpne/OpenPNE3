function url2cmd(url) {
    if (!url.match(/^http:\/\/www\.ebitv\.jp\/video.php\?id=([0-9]+)$/)) {
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
    html = '';
    html = '<iframe src="http://www.ebitv.jp/video_frame.php?id='
        + id
        + '" width="540" height="467" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>';
    document.writeln(html);
}

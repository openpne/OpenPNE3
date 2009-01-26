function main(id) {
    if (!id.match(/^[a-z0-9]+$/)) {
        return;
    }

    var html = '<script language="javascript" '
                +
'src="http://ppwin.so-net.ne.jp/webmail/petwindow/script.do?'
                + 'window_id='
                + id
                + '"></script>';
    document.write(html);
}

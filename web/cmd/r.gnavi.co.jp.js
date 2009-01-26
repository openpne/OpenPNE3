function url2cmd(url) {
    if (!url.match(/^http:\/\/r[.]gnavi[.]co[.]jp\/([a-z][0-9]+)\/?$/)) {
//        pne_url2a(url);
        document.write('<a href="' + url + '">' + url + '</a>');
        return;
    }
    var id = RegExp.$1;
    main(id);
}

function main(id) {
//    var url = 'http://cmd.encafe.jp/gnavi/?id=';
    var url = 'http://cmd.encafe.jp/g-plaza/?id=';
    
    if (!id.match(/^[a-z][0-9]+$/)) {
    document.write(url + id);
        return;
    }
    document.write('<iframe WIDTH="420" HEIGHT="177" MARGINWIDTH="0" MARGINHEIGHT="0" HSPACE="0" VSPACE="0" FRAMEBORDER="0" SCROLLING="no" BORDERCOLOR="#ffffff" src="' + url + id + '">');
    document.write('</iframe>');
}
function url2cmd(url) {
    if (!url.match(/^http:\/\/vote\.nifty\.com\/individual\/([0-9]+)\/([0-9]+)\/(.*)$/)){
        pne_url2a(url);
        return;
    }
    var id1 = RegExp.$1;
    var id2 = RegExp.$2;
    main(id1,id2);
}


function main(id1,id2) {
    if (!id1.match(/^[0-9]+$/) || !id2.match(/^[0-9]+$/)) {
        return;
    }


    var html = '<script type="text/javascript" charset="utf8" src="http://files.vote.nifty.com/individual/'  
            + id1
            + '/'
            + id2
            + '/vote.js"></script>';
    document.write(html);
}
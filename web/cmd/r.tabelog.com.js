function url2cmd(url) {
    if (url.match(/^http:\/\/r\.tabelog\.com\/[a-z]+\/A[0-9]+\/A[0-9]+\/([0-9]+)\/?$/) || url.match(/^http:\/\/r\.tabelog\.com\/[a-z]+\/rstdtl\/([0-9]+)\/?$/)) {
        var rcd = RegExp.$1;
        main(rcd);
        return;
    }
    pne_url2a(url);
    return;
}

function main(rcd) {
    if (!rcd.match(/^[0-9]+$/)) {
        return;
    }
    document.write('<iframe WIDTH="420" HEIGHT="280" MARGINWIDTH="0" MARGINHEIGHT="0" HSPACE="0" VSPACE="0" FRAMEBORDER="0" SCROLLING="no" BORDERCOLOR="#000000" src="http://api.tabelog.com/RstPNEParts/?RestaurantCD=' + rcd + '">');
    document.write('</iframe>');
}


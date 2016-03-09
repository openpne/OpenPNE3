//created by yasuda.
function url2cmd(url) {
    if (!url.match(/^http:\/\/kakaku\.com\/item\/([0-9A-Z]+)\/?$/)) {
        pne_url2a(url);
        return;
    }
    var productID = RegExp.$1;
    main(productID);
}

function main(productID) {
    if (!productID.match(/^[0-9A-Z]{11}$/)) {
        return;
    }
    document.write('<iframe WIDTH="420" HEIGHT="270" MARGINWIDTH="0" MARGINHEIGHT="0" HSPACE="0" VSPACE="0" FRAMEBORDER="0" SCROLLING="no" BORDERCOLOR="#000000" src="http://api.kakaku.com/blogparts/openpne/pne.ashx?ProductID=' + productID + '">');
    document.write('</iframe>');
}

// cmd for www.netprice.co.jp
function url2cmd(url) {
    if (url.match(/^http:\/\/www\.netprice\.co.jp\/netprice\/.*\/([\-0-9A-Za-z]+)\/?$/)) {
    	goods(url);
    } else {
        pne_url2a(url);
    }
    return;
}

function goods(url){
	//configuration
    var width = 420;
    var height = 300;
    var proxy_cgi = 'http://cmd-netprice.com/np.php?url=';
    
    //iframe
    var html = '<iframe WIDTH="' + width + '"'
    		+ ' HEIGHT="' + height + '"'
    		+ ' MARGINWIDTH="0" MARGINHEIGHT="0" HSPACE="0" VSPACE="0" FRAMEBORDER="0" SCROLLING="no"'
    		+ ' src="'+ proxy_cgi + url + '">'
    		+ ' </iframe>';
	document.write(html);
    return;
}

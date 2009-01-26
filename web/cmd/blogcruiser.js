function main(dirname, hid) {
    if (!dirname.match(/^[0-9]{3}$/)) {
       return;
    }
    if (!hid.match(/^[0-9]+$/)) {
        return;
    }
   
    var html = '<a href="http://blogcruiser.so-net.ne.jp/profile/view.do?toPersonalId=' 
                + hid
                + '" target="_blank">'
                + '<img src="http://blogcruiser.so-net.ne.jp/blogcruiser/image/card/'
                + dirname
                + '/'
                + hid
                + '.png?card" alt="ブログクルーザー" border="0" /></a>'
    document.write(html);
}

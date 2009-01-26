/**
 * Amazon review CMD
 *
 * @license http://www.php.net/license/3_0.txt PHP License ver3.0
 * @copyright 2007-2008 imoto<imoto@tejimaya.com>
 * @copyright 2007-2008 Tejimaya .inc
 * @author Imoto<imoto@tejimaya.com>
 * @author Mamoru Tejima<tejima@tejimaya.com>
 */

function url2cmd(url)
{
    var id ;
    var tag;
    var match_id = url.match(/(?:ASIN|product|dp)\/([^\/]+)/i);
    if (match_id) {
        id = RegExp.$1;
    }

    var match_tag = url.match(/tag=([a-zA-Z0-9_\-]+)/);
    if (match_tag) {
        tag = RegExp.$1;
    } else {
        tag = '';
    }

    if (id) {
        main(id, tag);
    } else {
        pne_url2a(url);
    }
}

function main(id, tag)
{
    var url = 'http://amazon.openpne.jp/?id=' + id;
    if (tag) {
        url += '&tag=' + tag;
    }

    var html = ''
    + '<iframe MARGINWIDTH="0" MARGINHEIGHT="0" HSPACE="0" VSPACE="0" FRAMEBORDER="0" SCROLLING="no" BORDERCOLOR="#000000" src="' + url + '" name="sample" width="420" height="320">'
    + 'この部分はインラインフレームを使用しています。'
    + '</iframe>';

    document.write(html);
}

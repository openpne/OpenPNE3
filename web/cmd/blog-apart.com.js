document.write('<script type="text/javascript" src="http://blog-apart.com/INVADERS/invaders.js"></sc' + 'ript>');
document.write('<script type="text/javascript" src="http://blog-apart.com/PINGxPONG/pingpong.js"></sc' + 'ript>');
document.write('<script type="text/javascript" src="http://blog-apart.com/OX/ox.js"></sc' + 'ript>');
document.write('<script type="text/javascript" src="http://blog-apart.com/SWEEPER/sweeper.js"></sc' + 'ript>');
document.write('<script type="text/javascript" src="http://blog-apart.com/PAINT_BIT/paint_bit.js"></sc' + 'ript>');

function url2cmd(url) {
    if (!url.match(/^http:\/\/(?:www\.|)blog\-apart\.com\/([a-zA-Z0-9_\-]+)\//)) {
        pne_url2a(url);
        return false;
    }
    var id = RegExp.$1;
    if (!main(id)) {
        pne_url2a(url);
        return false;
    }
    return true;
}

function main(id) {
    if (!id.match(/^[a-zA-Z0-9_\-]+$/)) {
        return false;
    }
    switch (id) {
    case 'INVADERS':
        invaders_function();
        break;
    case 'PINGxPONG':
        pingpong_function();
        break;
    case 'OX':
        ox_function();
        break;
    case 'SWEEPER':
        sweeper_function();
        break;
    case 'PAINT_BIT':
        paint_bit_function();
        break;
    default:
        return false;
    }
    return true;
}

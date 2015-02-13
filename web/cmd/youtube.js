function main(id, width, height) {
    if (!id.match(/^[a-zA-Z0-9_\-]+$/)) {
        return;
    }
    if (!width) width = 0; else width = parseInt(width);
    if (!height) height = 0; else height = parseInt(height);
    if (width <= 0 || width > 425) {
        width = 425;
    }
    if (height <= 0 || height > 350) {
        height = 350;
    }
    var html = '<iframe width="'
            + width
            + '" height="'
            + height
            + '" src="//www.youtube.com/embed/'
            + id
            + '" frameborder="0" allowfullscreen="allowfullscreen"></iframe>';
    document.write(html);
}

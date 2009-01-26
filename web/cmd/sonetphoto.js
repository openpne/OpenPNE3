function main(member_name, type, sort) {
    if (!member_name.match(/^[a-zA-Z0-9_\-]+$/)) {
        return;
    }
    if (type == "t" || type == "thumbnail") {
        type = "thumbnail" ;
        var img_width = "36";
        var img_height = "36";
    } else if (type == "s" || type == "slideshow") {
        type = "slideshow";
        var img_width = "240";
        var img_height = "240";
    } else {
        type = "thumbnail" ;
        var img_width = "36";
        var img_height = "36";
    }
    if (sort == "n" || sort == "new") {
        sort = "new" ;
    } else if (sort == "r" || sort == "random") {
        sort = "random";
    } else {
        sort = "new" ;
    }
    var html = '<script type="text/javascript">'
                + 'sonetphoto_badge_member_name = "'
                + member_name
                + '"; sonetphoto_badge_type = "'
                + type
                + '"; sonetphoto_badge_target = "imagelist"; sonetphoto_badge_sort = "'
                + sort
                + '"; sonetphoto_badge_img_width = "'
                + img_width
                + '"; sonetphoto_badge_img_height = "'
                + img_height
                + '"; sonetphoto_badge_color_bg = "FFFFFF"; sonetphoto_badge_color_border = "AAAAAA"; ';
    if (type == "slideshow") {
        html += 'sonetphoto_badge_color_titletext = "FFFFFF"; sonetphoto_badge_color_titlebg = "B8B8B8"; sonetphoto_badge_trim = "0"; ';
    }
    html += 'sonetphoto_badge_rounded = "0";</script><script type="text/javascript" src="http://pht.so-net.ne.jp/bp/sonetphoto_badge_1_0.js"></script>';
    document.write(html);
}

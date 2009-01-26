function main(id) {
     if (!id.match(/^ID[0-9]+$/)) {
         return;
     }

     var html = '<script type="text/javascript"; language="javascript"'
                 + 'src="http://tagfriends.com/'
                 + id
                 + '"></script>';
     document.write(html);
}

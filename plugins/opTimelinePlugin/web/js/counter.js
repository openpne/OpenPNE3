/*
 *  The counter of textarea
 *
 *  @parts  :opTimelinePlugin
 *  @author :Yudai Sonoda <webmaster@net-top.jp>
 */

$(function ()
  {

    // Configuration Properties
    var allowed = 140;
    var warning = 25;
    var textarea = $('#timeline-textarea');
    var counter = $('#counter');

    counter.text(allowed);
    textarea.keyup(function ()
      {
         var bodyLen = $(this).val().length;
         if ($(this).val().match(/\n/gm))
         {
           bodyLen = bodyLen + $(this).val().match(/\n/gm).length;
         }
         var count = (allowed - bodyLen);
         counter.text(count);
         if (count <= warning && count >= 0) {
           counter.css({
             color : '#FFA500'
           })
         }
         else if (count < 0) {
           counter.css({
             color : '#FF0000'
           })
         }
         else {
           counter.css({
             color : '#000000'
           })
         }
     });
});

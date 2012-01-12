$(document).ready(function(){
  $(".ncbutton").click(function(){
    $(".toggle1:not(.ncform)").hide();
    $(".ncform").toggle();
  });

  $(".menubutton").click(function(){
    $(".toggle1:not(.menuform)").hide();
    $(".menuform").toggle();
  });

  $(".toggle1_close").click(function(){
    $(".toggle1").hide();
  });
});

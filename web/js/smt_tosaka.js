$(document).ready(function(){
  $(".ncbutton").click(function(){
    $(".toggle1:not(.ncform)").hide();
    $(".ncform").toggle();
    $('#pushLoading').show();
    $.getJSON( openpne.apiBase + 'push/search.json?apiKey=' + openpne.apiKey, function(json){
      if(json.status=='success')
      {
        $pushHtml = $("#pushListTemplate").tmpl(json.data);
	$('.friend-accept', $pushHtml).friendLink({ buttonElement: '.friend-notify-button', ncfriendloadingElement: '#ncfriendloading', ncfriendresultmessageElement: '#ncfriendresultmessage', });
	$('.friend-reject', $pushHtml).friendUnlink({ buttonElement: '.friend-notify-button', ncfriendloadingElement: '#ncfriendloading', ncfriendresultmessageElement: '#ncfriendresultmessage', })
        $("#pushList").html($pushHtml);
      }else{
        alert(json.message);
      }
      $('.nclink').pushLink();
      $('#pushList').show();
      $('#pushLoading').hide();
    });
  });

  $(".menubutton").click(function(){
    $(".toggle1:not(.menuform)").hide();
    $(".menuform").toggle();
  });

  $(".postbutton").click(function(){
    $(".toggle1:not(.postform)").hide();
    $(".postform").toggle();
    if($(".postform").is(":visible")){
      $(".posttextarea").focus();
    }
  });

  $(".toggle1_close").click(function(){
    $(".toggle1").hide();
  });

  $('#tosaka_postform_submit').click(function() {
    var body_elem = $('#tosaka_postform_body');
    var body_text = body_elem.val();
    if (body_text == '') return;

    var params = {
      apiKey: openpne.apiKey,
      body: body_text
    };

    $.getJSON(openpne.apiBase + 'activity/post.json', params, function(json) {
      if (json.status == 'success') {
        body_elem.val('');
      }
    });
  });

  $.getJSON( openpne.apiBase + 'push/count.json?apiKey=' + openpne.apiKey, function(json){
    if(json.status=='success')
    {
      $pushHtml = $("#pushCountTemplate").tmpl(json.data);
      $("#notification_center").append($pushHtml);
    }else{
      alert(json.message);
    }
  });
});

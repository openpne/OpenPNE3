$(document).ready(function(){
  $(".ncbutton").click(function(){
    $(".toggle1:not(.ncform)").hide();
    $(".ncform").toggle();
    $('#pushLoading').show();
    if('none' !== $('.ncform').css('display'))
    {
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
    }
    collapse_toggle($('.nav-collapse'));
  });

  $(".btn-navbar").click(function(){
    $(".toggle1:not(.nav-collapse)").hide();
    $('.nav-collapse').show();
    collapse_toggle($('.nav-collapse'));
  });

  $(".postbutton").click(function(){
    $(".toggle1:not(.postform)").hide();
    $(".postform").toggle();
    if($(".postform").is(":visible")){
      $(".posttextarea").focus();
    }
    collapse_toggle($('.nav-collapse'));
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
    if ($('#tosaka_postform_submit').attr('data-community-id'))
    {
      params.target = 'community';
      params.target_id = $('#tosaka_postform_submit').attr('data-community-id');
    }

    $.getJSON(openpne.apiBase + 'activity/post.json', params, function(json) {
      if (json.status == 'success') {
        body_elem.val('');
        $(".postform").toggle();
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

function collapse_toggle(elm)
{
  if (elm.hasClass('collapse'))
  {
    elm.removeClass('collapse');
  }
  else
  {
    elm.addClass('collapse');
  }
}


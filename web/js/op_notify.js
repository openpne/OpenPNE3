$(document).ready(function(){
  var is_read_flag = false;
  $.getJSON( openpne.apiBase + 'push/count.json?apiKey=' + openpne.apiKey, function(json){
    if(json.status=='success')
    {
      $pushHtml = $("#notificationCenterCountTemplate").tmpl(json.data);
      $("#notificationCenter").append($pushHtml);
    }
  });

  $('.ncbutton').click(function(){
    if ('none' == $('#notificationCenterDetail').css('display'))
    {
      $('#notificationCenterDetail').show();
      if (is_read_flag == false)
      {
        $.getJSON( openpne.apiBase + 'push/search.json?apiKey=' + openpne.apiKey, function(json){
          if(json.status=='success')
          {
            if(json.data[0])
            {
              $pushHtml = $('#notificationCenterListTemplate').tmpl(json.data);
              $('.friend-accept', $pushHtml).friendLink();
              $('.friend-reject', $pushHtml).friendUnlink()
              $('#notificationCenterLoading').hide();
              $('#notificationCenterError').hide();
              $('#notificationCenterDetail').append($pushHtml);
            }else{
              $('#notificationCenterLoading').hide();
              $('#notificationCenterError').show();
            }            
          }else{
            $('#notificationCenterLoading').hide();
            $('#notificationCenterError').show();
          }
          $('.nclink').pushLink();
        });
        is_read_flag = true;
      }
    }
    else
    {
      $('#notificationCenterDetail').hide();
    }
  });
});

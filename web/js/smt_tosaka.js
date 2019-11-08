var TosakaView = Backbone.View.extend({
  events: {
    'click .ncbutton': 'toggleNotify',
    'click .btn-navbar': 'toggleMenu',
    'click .postbutton': 'togglePostform',
    'click .toggle1_close': 'close',
    'click #tosaka_postform_submit': 'post'
  },
  initialize:function() {
    _.bindAll(this, 'toggleNotify', 'toggleMenu', 'togglePostform', 'close', 'post');

    op.api.getJSON('push/count.json', function(json){
      if(json.status=='success')
      {
        $pushHtml = $("#pushCountTemplate").tmpl(json.data);
        $("#notification_center").append($pushHtml);
      }else{
        alert(json.message);
      }
    });
  },
  toggleNotify: function() {
    $(".toggle1:not(.ncform)").hide();
    $(".ncform").toggle();
    $('#pushLoading').show();
    if('none' !== $('.ncform').css('display'))
    {
      op.api.getJSON('push/search.json', function(json){
        if(json.status=='success')
        {
          $pushHtml = $("#pushListTemplate").tmpl(json.data);
          var param = {
            buttonElement: '.friend-notify-button',
            ncfriendloadingElement: '#ncfriendloading',
            ncfriendresultmessageElement: '#ncfriendresultmessage'
          };
          $('.friend-accept', $pushHtml).friendLink(param);
          $('.friend-reject', $pushHtml).friendUnlink(param);
          $("#pushList").html($pushHtml);
        }else{
          alert(json.message);
        }
        $('.nclink').pushLink();
        $('#pushList').show();
        $('#pushLoading').hide();
      });
    }
    this._collapse_toggle($('.nav-collapse'));
  },
  toggleMenu: function() {
    $(".toggle1:not(.nav-collapse)").hide();
    $('.nav-collapse').show();
    this._collapse_toggle($('.nav-collapse'));
  },
  togglePostform: function() {
    $(".toggle1:not(.postform)").hide();
    $(".postform").toggle();
    if($(".postform").is(":visible")){
      $(".posttextarea").focus();
    }
    this._collapse_toggle($('.nav-collapse'));
  },
  close: function() {
    $(".toggle1").hide();
  },
  post: function() {
    var body_elem = $('#tosaka_postform_body');
    var body_text = body_elem.val();
    if (body_text == '') return;

    var params = {
      body: body_text
    };
    if ($('#tosaka_postform_submit').attr('data-community-id'))
    {
      params.target = 'community';
      params.target_id = $('#tosaka_postform_submit').attr('data-community-id');
    }

    op.api.getJSON('activity/post.json', params, function(json) {
      if (json.status == 'success') {
        body_elem.val('');
        $(".postform").toggle();
      }
    });
  },
  _collapse_toggle: function(elm) {
    if (elm.hasClass('collapse'))
    {
      elm.removeClass('collapse');
    }
    else
    {
      elm.addClass('collapse');
    }
  }
});

$(document).ready(function() {
  var tosakaView = new TosakaView({el:$(document)});
});

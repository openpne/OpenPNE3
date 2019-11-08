var NotifyView = Backbone.View.extend({
  events: {
    'click .ncbutton': 'toggleNotify'
  },
  initialize:function() {
    _.bindAll(this, 'toggleNotify');
    op.api.getJSON('push/count.json', function(json){
      if(json.status=='success')
      {
        $pushHtml = $("#notificationCenterCountTemplate").tmpl(json.data);
        $("#notificationCenter").append($pushHtml);
      }
    });
  },
  toggleNotify: function() {
    $('#notificationCenterDetail').toggle();
    this._getNotify();
  },
  _getNotify: _.once(function() {
    op.api.getJSON('push/search.json', function(json){
      if(json.status === 'success' && json.data[0]) {
        $pushHtml = $('#notificationCenterListTemplate').tmpl(json.data);
        $('.friend-accept', $pushHtml).friendLink();
        $('.friend-reject', $pushHtml).friendUnlink()
        $('#notificationCenterError').hide();
        $('#notificationCenterDetail').append($pushHtml);
      }else{
        $('#notificationCenterError').show();
      }
      $('#notificationCenterLoading').hide();
      $('.nclink').pushLink();
    });
  })
});

$(document).ready(function() {
  var notifyView = new NotifyView({el:$('#notificationCenter')});
});

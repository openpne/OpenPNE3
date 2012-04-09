/*******************************************************
** jQuery notification center functions
** how to use : $('#element').pushLink();
**              $('#element').friendLink(buttonElement: '.button', ncfriendloadingElement: '#ncfriendloading', ncfriendresultmessageElement: '#ncfriendresultmessage');
**              $('#element').friendUnlink(buttonElement: '.button', ncfriendloadingElement: '#ncfriendloading', ncfriendresultmessageElement: '#ncfriendresultmessage');
** @author    : Shouta Kashiwagi <kashiwagi@tejimaya.com>
*********************************************************/

(function($){
	var _followScroll = true;
	var _readyBound = false;

	$.fn.pushLink = function(settings){

		settings = $.extend({
			isDisableRead: false,
		}, settings);

		return this.each(function(){
			var linkUrl = $(this).attr('data-location-url');
			var notifyId = $(this).attr('data-notify-id');
			$(this).mouseover(function(){
				$(this).addClass('hover');
			})
			.mouseout(function(){
				$(this).removeClass('hover');
			})
			$(this).click(function(){
				if ( false == settings.isDisableRead )
                                {
					$.getJSON( openpne.apiBase + 'push/read.json' , { 'id': notifyId, 'apiKey': openpne.apiKey }, function(d){
						window.location = linkUrl;
					});
				}
				else
				{
					window.location = linkUrl;
				}
			});
		});
	};

	$.fn.friendLink = function(settings){
		return this.each(function(){
			$(this).click(function(){
				$(this).parent().find('.friend-notify-button').hide();
				$(this).parent().find('.ncfriendloading:first').show();
			        var pushElement = $(this).parents('.push');
				var memberId = pushElement.attr('data-member-id');
				var notifyId = pushElement.attr('data-notify-id');
				$.getJSON( openpne.apiBase + 'push/read.json' , { 'id': notifyId, 'apiKey': openpne.apiKey }, function(d){});
				$.ajax({
					url: openpne.apiBase + 'member/friend_accept.json',
					type: 'GET',
					data: 'member_id=' + memberId + '&apiKey=' + openpne.apiKey,
					dataType: 'json',
					success: function(data) {
						if(data.status=='success'){
							$('.ncfriendloading').hide();
							$(this).parent().find('.ncfriendresultmessage').text('フレンド申請を承認しました。');
							$(this).parent().find('.ncfriendresultmessage').show();
						}else{
							alert(data.message);
						}   
					},
					error: function(r, s, e){
						$('.ncfriendloading').hide();
						$(this).parent().find('.ncfriendresultmessage').text('既に承認済みか拒否済みです。');
						$(this).parent().find('.ncfriendresultmessage').show(); 
                                        }
				});
			});
		});
  };

	$.fn.friendUnlink = function(settings){
		return this.each(function(){
			$(this).click(function(){
				$(this).parent().find('.friend-notify-button').hide();
				$(this).parent().find('.ncfriendloading:first').show();
			        var pushElement = $(this).parents('.push');
				var memberId = pushElement.attr('data-member-id');
				var notifyId = pushElement.attr('data-notify-id');
				$.getJSON( openpne.apiBase + 'push/read.json' , { 'id': notifyId, 'apiKey': openpne.apiKey }, function(d){});
				$.ajax({
					url: openpne.apiBase + 'member/friend_reject.json',
					type: 'GET',
					data: 'member_id=' + memberId + '&apiKey=' + openpne.apiKey,
					dataType: 'json',
					success: function(data) {
						if(data.status=='success'){
							$('.ncfriendloading').hide();
							$(this).parent().find('.ncfriendresultmessage').text('フレンド申請を拒否しました。');
							$(this).parent().find('.ncfriendresultmessage').show();
						}else{
							alert(data.message);
						}   
					},
					error: function(r, s, e){
						$('.ncfriendloading').hide();
						$(this).parent().find('.ncfriendresultmessage').text('既に承認済みか拒否済みです。');
						$(this).parent().find('.ncfriendresultmessage').show(); 
                                        }
				});
			});
		});
  };

})(jQuery);

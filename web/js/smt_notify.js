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
				$.getJSON( openpne.apiBase + 'push/read.json' , { 'id': notifyId, 'apiKey': openpne.apiKey }, function(d){});
				window.location = linkUrl;
			});
		});
	};

	$.fn.friendLink = function(settings){
		return this.each(function(){
			$(this).click(function(){
				$(settings.buttonElement).hide();
				$(settings.ncfriendloadingElement).show();
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
							$(settings.ncfriendloadingElement).hide();
							$(settings.ncfriendresultmessageElement).text('フレンド申請を承認しました。');
						}else{
							alert(data.message);
						}   
					}
				});
			});
		});
        };


	$.fn.friendUnlink = function(settings){
		return this.each(function(){
			$(this).click(function(){
				$(settings.buttonElement).hide();
				$(settings.ncfriendloadingElement).show();
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
							$(settings.ncfriendloadingElement).hide();
							$(settings.ncfriendresultmessageElement).show();
							$(settings.ncfriendresultmessageElement).text('フレンド申請を拒否しました。');
						}else{
							alert(data.message);
						}   
					}
				});
			});
		});
        };

})(jQuery);

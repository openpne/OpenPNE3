/*********************************************************
** jQuery timelinePlugin functions
** how to use : $('#element').timelineComment();
**              $('#element').timelineDelete();
** @parts     : opTimelinePlugin
** @author    : Shouta Kashiwagi <kashiwagi@tejimaya.com>
**********************************************************/
(function($){

  $.fn.timelineComment = function(settings){

    return this.each(function(){
      $(this).click(function(){
        var id = $(this).attr("data-timeline-id");
        var foreign = $(this).attr('data-activity-foreign');
        var foreignId = $(this).attr('data-activity-foreign-id');
        var body = $('#comment-textarea-'+id).val();
        $timelineLoader = $(this).parent().next();
        $timelineLoader.next().text('');
        $timelineLoader.next().hide();
        $timelineLoader.show();
        $(this).parent().hide();
        $(this).attr('disabled', 'disabled');
        $.ajax({
          url: openpne.apiBase + 'activity/post.json',
          type: 'POST',
          data: {
            body: body,
            in_reply_to_activity_id: id,
            apiKey: openpne.apiKey,
            target: foreign,
            target_id: foreignId
          },
          dataType: 'json',
          success: function(data) {
            if ('success' == data.status)
            {
              data.data.body_html = data.data.body;
              $('#comment-textarea-'+id).val('');
              $timelineLoader.hide();
              $('#timeline-post-comment-form-'+id).show();
              $postData = $('#timelineCommentTemplate').tmpl(data.data);
              $('#timeline-post-comment-form-'+id).before($postData);

              $('button.timeline-post-delete-button').timelineDelete();
              $('.timeline-post-delete-confirm-link').colorbox({
                inline: true,
                width: '610px',
                opacity: '0.8',
                onOpen: function(){ 
                  $($(this).attr('href')).show(); 
                },
                onCleanup: function(){ 
                  $($(this).attr('href')).hide();
                },
                onClosed: function(){
                  timelineAllLoad();
                }
              });
            }
            else
            {
              $timelineLoader.hide();
              $timelineLoader.next().text('投稿に失敗しました');
              $timelineLoader.next().show();
              $timelineLoader.prev().show();
            }
          },
          error: function(x, r, t) {
            $timelineLoader.hide();
            $timelineLoader.next().text('投稿に失敗しました');
            $timelineLoader.next().show();
            $timelineLoader.prev().show();
          }
        });

        return false;
      });
    });
  };

  $.fn.timelineDelete = function() {

    return this.each(function(){
      $(this).click(function(){
      $(this).hide();
      $(this).parent().next().show();
      var activity_id = $(this).attr('data-activity-id');
        $.ajax({
          url: openpne.apiBase + 'activity/delete.json',
          type: 'POST',
          data: {
            activity_id: activity_id,
            apiKey: openpne.apiKey
          },
          dataType: 'json',
          success: function(data) {
            $(this).parent().next().hide();
            $.colorbox.close();
            timelineAllLoad();
          },
          error: function(x, r, t) {
            $(this).parent().next().hide();
            $.colorbox.close();
          }
        });
      });
    });
  };

})(jQuery);

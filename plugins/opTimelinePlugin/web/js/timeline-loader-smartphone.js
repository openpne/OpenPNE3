$(function(){
  gorgon.image_size = 'small';

  timelineAllLoad();

  $('.basic-mode').remove();
  $('.timeline-mode').show();

  if ((navigator.userAgent.indexOf('iPhone') > 0 && navigator.userAgent.indexOf('iPad') == -1) || navigator.userAgent.indexOf('iPod') > 0) {
    $('#timeline-upload-photo-button').hide();
    $('#timeline-public-flag').css('margin', '0 0 0 0');
  }

  $('#timeline_postform_submit').click( function() {
    $('#timeline-submit-error').hide();
    $('.photo-info').hide();
    $('#timeline_postform_submit').attr('disabled', 'disabled');

    var body = $('#tosaka_postform_body').val();
    var a = $('<div />');
    var flushBody = a.text(body).html();
    body = body.replace(/"/g, '&quot;')

    var faceName = $('.face-name').text();
    var faceImg = $('#face').children('.span2').children('img').attr('src');
    var flashTimelineDom =
          '<div class="flashTimelineDom">'
          + '<div class="timeline-post-member-image">'
            + '<img src="' + faceImg + '" alt="member-image" width="23" />'
          + '</div>'
          + '<div class="timeline-post-content">'
            + '<div class="timeline-member-name">'
              + '<a>' + faceName + '</a>'
              + '<div class="timestamp">1分前</div>'
            + '</div>'
            + '<div class="timeline-post-body">' + flushBody + '</div>'
            + '<span class="timeline-post-control">'
              + '<img style="float: right; padding-right: 20px;" src="/images/ajax-loader.gif" />'
            + '</span>'
          + '</div>'
          + '<div class="timeline-post-control">'
            + '<a class="timeline-comment-link"></a>'
          + '</div>'
        + '</div>';

    if (0 < jQuery.trim(body).length)
    {
      $('#timeline-list').prepend(flashTimelineDom);
    }

    if (gorgon)
    {
      var publicFlag = 1;
      if ('community' != gorgon.target)
      {
        publicFlag = $('#timeline-public-flag option:selected').val()
      }

      var data = {
        body: body,
        target: gorgon.post.foreign,
        target_id: gorgon.post.foreignId,
        apiKey: openpne.apiKey,
        public_flag: publicFlag
      };
    }
    else
    {
      var data = {
        body: body,
        apiKey: openpne.apiKey
      };
    }
    tweetByData(data);
  });

  $('#timeline-upload-photo-button').click(function() {
    $('#timeline-submit-upload').click();
    $('#photo-remove').show();
  });

  $('#timeline-submit-upload').change(function() {

      $('#timeline-submit-error').hide();
      $('#timeline-submit-error').text('');

      var size = this.files[0].size;

      if (size >= fileMaxSizeInfo['size']) {
        $('#timeline-submit-error').show();

        var errorMessage = 'ファイルは' + fileMaxSizeInfo['format'] + '以上はアップロードできません';
        $('#timeline-submit-error').text(errorMessage);

        $('#timeline-submit-upload').val('');
        $('#photo-file-name').text('');

      }

  });

  $('#gorgon-loadmore').click( function() {
    $('#timeline-list-loader').show();
    $('#gorgon-loadmore').hide();
    timelineLoadmore();
    $('#timeline-list-loader').hide();
    $('#gorgon-loadmore').show();
  });

  $(document).on('click', '.timeline-comment-loadmore', function() {
    var timelineId = $(this).attr('data-timeline-id');
    var commentlist = $('#commentlist-' + timelineId);
    var commentLength = commentlist.children('.timeline-post-comment').length;
    $('#timeline-comment-loader-' + timelineId).show();

    $.ajax({
      type: 'GET',
      url: openpne.apiBase + 'activity/commentSearch.json?apiKey=' + openpne.apiKey,
      data: {
        'timeline_id': timelineId,
        'count': commentLength + 20
      },
      success: function(json){
        commentlist.children('.timeline-post-comment').remove();
        $('#timelineCommentTemplate').tmpl(json.data.reverse()).prependTo('#commentlist-' + timelineId);
        $('#timeline-comment-loader-' + timelineId).hide();

        if (json.data.length < commentLength + 20)
        {
          $('#timeline-comment-loadmore-' + timelineId).hide();
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){
        $('#commentlist-' + timelineId).hide();
      }
    });
  });

  $('#tosaka_postform_body').keyup( function() {
    lengthCheck($(this), $('#timeline_postform_submit'));
  });

  $(document).on('keyup', '.timeline-post-comment-form-input', function() {
    lengthCheck($(this), $('button[data-timeline-id=' + $(this).attr('data-timeline-id') + ']'));
  });

  $('#timeline-submit-upload').change(function() {
    var fileName = $('#timeline-submit-upload').val();
    var dom = $('#photo-file-name');
    if (20 > fileName.length)
    {
      dom.text(fileName);
    }
    else
    {
      dom.text(fileName.substring(0, 19) + '…');
    }
    $('.photo-info').show();
  });

  $('#photo-remove').click( function() {
    $('#timeline-submit-upload').val('');
    $('#photo-file-name').text('');
    $(this).hide();
  });
});

function timelineAllLoad() {
  if (gorgon)
  {
    gorgon.apiKey = openpne.apiKey;
    $.ajax({
      type: 'GET',
      url: openpne.apiBase + 'activity/search.json',
      data: gorgon,
      success: function (json){
        renderJSON(json, 'all');
        $('#timeline-list-loader').hide();
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){
        $('#timeline-list-loader').hide();
        $('#timeline-list').text('投稿されていません。');
        $('#timeline-list').show();
        $('.flashTimelineDom').remove();
      }
    });

  }
  else
  {
    $.ajax({
      type: 'GET',
      url: openpne.apiBase + 'activity/search.json?apiKey=' + openpne.apiKey,
      success: function (json){
        renderJSON(json, 'all');
        $('#timeline-list-loader').hide();
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){
        $('#timeline-list-loader').hide();
        $('#timeline-list').text('投稿されていません。');
        $('#timeline-list').show();
        $('.flashTimelineDom').remove();
      }
    });
  }
}

function timelineLoadmore() {
  var loadmoreId = $('#timeline-list').attr('data-loadmore-id');
  loadmoreId = loadmoreId - 1;
  if (gorgon)
  {
    gorgon.apiKey = openpne.apiKey;
  }
  else
  {
    gorgon = {apiKey: openpne.apiKey}
  }
  gorgon.max_id = loadmoreId;

  $.ajax({
    type: 'GET',
    url: openpne.apiBase + 'activity/search.json',
    data: gorgon,
    success: function(json){
      renderJSON(json, 'more');
      $('#timeline-loadmore-loading').hide();
    },
    error: function(XMLHttpRequest, textStatus, errorThrown){
      $('#timeline-loadmore-loading').hide();
    }
  });
}

function renderJSON(json, mode) {
  if ('undefined' === typeof mode)
  {
    mode = 'all';
  }
  if ('all' == mode)
  {
    $('#timeline-list').empty();
  }
  if(json.data && 0 < viewPhoto)
  {
    for(var i = 0; i < json.data.length; i++)
    {
      if (!json.data[i].body_html.match(/img.*src=/))
      {
        if (json.data[i].body.match(/\.(jpg|jpeg|bmg|png|gif)/gi))
        {
          json.data[i].body_html = json.data[i].body.replace(/((http:|https:)\/\/[\x21-\x26\x28-\x7e]+.(jpg|jpeg|bmg|png|gif))/gi, '<div><a href="$1"><img src="$1" /></a></div>');
        }
        else if (json.data[i].body.match(/((http:|https:)\/\/[\x21-\x26\x28-\x7e]+)/gi))
        {
          json.data[i].body_html = json.data[i].body.replace(/((http:|https:)\/\/[\x21-\x26\x28-\x7e]+)/gi, '<a href="$1"><div class="urlBlock"><img src="http://mozshot.nemui.org/shot?$1" /><br />$1</div></a>');
        }
      }
      json.data[i].body_html = json.data[i].body_html.replace(/&lt;br \/&gt;/g, '<br />');
    }
  }


  $timelineData = $('#timelineTemplate').tmpl(json.data);
  $('.timeline-comment-button', $timelineData).timelineComment();
  $('.timeline-comment-link', $timelineData).click(function(){
    $commentBoxArea = $(this).parent().siblings().find('.timeline-post-comment-form');
    $commentBoxArea.show();
    $commentBoxArea.children('.timeline-post-comment-form-input').focus();
  });
  if ('diff' == mode)
  {
    $timelineData.prependTo('#timeline-list');
  }
  else
  {
    $timelineData.appendTo('#timeline-list');
  }
  if ('all' == mode || 'diff' == mode)
  {
    if(json.data[0])
    {
      $('#timeline-list').attr('data-last-id', json.data[0].id);
    }
  }
  if ('all' == mode || 'more' == mode)
  {
    var max = json.data.length - 1;
    if (json.data[max])
    {
      $('#timeline-list').attr('data-loadmore-id', json.data[max].id);
    }
  }
  if(json.data)
  {
    for(var i = 0; i < json.data.length; i++)
    {
      if(json.data[i].replies)
      {
        $('#timelineCommentTemplate').tmpl(json.data[i].replies.reverse()).prependTo('#commentlist-' +json.data[i].id);
        $('#timeline-post-comment-form-' + json.data[i].id, $timelineData).show();
      }
      if(10 < parseInt(json.data[i].replies_count))
      {
        $('#timeline-comment-loadmore-' + json.data[i].id).show();
      }
    }
  }
  if ('all' == mode)
  {
    $('#timeline-loading').hide();
  }
  if ('more' == mode)
  {
    $('#timeline-loadmore').show();
    $('#timeline-loadmore-loading').hide();
  }
  $('.timeago').timeago();
}

function tweetByData(data)
{
  //reference　http://lagoscript.org/jquery/upload/documentation
  $('#timeline-submit-upload').upload(
    openpne.apiBase + 'activity/post.json', data,
    function (res) {
      var resCheck = responseCheck(res);
      if (false !== resCheck)
      {
        $('#timeline-submit-error').text(resCheck);
        $('#timeline-submit-error').show();
        $('.flashTimelineDom').remove();
        return;
      }

      res = res.replace(/,\"body.*\"/g, '');
      returnData = JSON.parse(res);

      if (returnData.status === "error") {

        var errorMessages = {
          file_size: 'ファイルサイズは' + fileMaxSizeInfo['format'] + 'までです',
          upload: 'アップロードに失敗しました',
          not_image: '画像をアップロードしてください',
          tweet: '投稿に失敗しました'
        };

        var errorType = returnData.type;

        $('#timeline-submit-error').text(errorMessages[errorType]);
        $('#timeline-submit-error').show();
        $('.flashTimelineDom').remove();

      } else {
        $(".postform").toggle();
        timelineAllLoad();
      }

      $('#timeline-submit-upload').val('');
      $('#tosaka_postform_body').val('');
      $('#timeline-submit-loader').hide();
      $('#counter').text(MAXLENGTH);

    },
    'text'
    );
}

function lengthCheck(obj, target)
{
  var objLength = obj.val().length;
  if (obj.val().match(/\n/gm))
  {
    objLength = objLength + obj.val().match(/\n/gm).length;
  }

  if (0 < objLength && MAXLENGTH >= objLength)
  {
    target.removeAttr('disabled');
  }
  else
  {
    target.attr('disabled', 'disabled');
  }
}

function responseCheck(res)
{
  var matchResult = res.match(/\\<pre/);
  if (null != matchResult)
  {
    return 'エラーが発生しました。再度読み込んで下さい。';
  }
  return false;
}

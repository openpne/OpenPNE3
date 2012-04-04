<?php use_helper('Javascript') ?>
<script id="friendListTemplate" type="text/x-jquery-tmpl">
  <div class="span3">
    <div class="row_memberimg row"><div class="span3 center"><a href="${profile_url}"><img src="${profile_image}" class="rad10" width="57" height="57" /></a></div></div>
    <div class="row_membername font10 row"><div class="span3 center"><a href="${profile_url}">${name}</a> (${friends_count})</div></div>
  </div>
</script>
<script type="text/javascript">
$(function(){
  $.getJSON( openpne.apiBase + 'member/search.json?target=friend&target_id=<?php echo $member->getId() ?>&apiKey=' + openpne.apiKey, function(json) {
    $result = $('#friendListTemplate').tmpl(json.data);
    $('#memberFriendList').html($result);
    $('#memberFriendList').show();
    $('#memberFriendListLoading').hide();
  });
  $('#memberFriendSearch').keypress(function(){
    $('#memberFriendListLoading').show();
    $('#memberFriendList').hide();
    $('#memberFriendList').empty();
  });
  $('#memberFriendSearch').blur(function(){
    var keyword = $('#memberFriendSearch').val();
    var requestData = { target: 'friend', target_id: <?php echo $member->getId(); ?>, keyword: keyword, apiKey: openpne.apiKey };
    $.getJSON( openpne.apiBase + 'member/search.json', requestData, function(json) {
      $result = $('#friendListTemplate').tmpl(json.data);
      $('#memberFriendList').html($result);
      $('#memberFriendList').show();
      $('#memberFriendListLoading').hide();
    });
  });
});
</script>

<hr class="toumei" />
<div class="row">
  <div class="gadget_header span12"><?php echo __('%friend% List', array('%friend%' => $op_term['friend'])) ?></div>
</div>
<hr class="toumei" />
<div class="row" id="memberFriendSearchBox">
<div class="input-prepend span12">
<span class="add-on"><i class="icon-search"></i></span>
<input type="text" id="memberFriendSearch" class="realtime-searchbox" value="" />
</div>
</div> 
<div class="row" id="memberFriendList">
</div> 
<div class="row" id="memberFriendListLoading" style="margin-left: 0; text-align: center;">
<?php echo op_image_tag('ajax-loader.gif') ?>
</div>


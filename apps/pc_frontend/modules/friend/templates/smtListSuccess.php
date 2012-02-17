<script id="friendListTemplate" type="text/x-jquery-tmpl">
  <div class="span3">
    <div class="row_memberimg row"><a href="${profile_url}"><img src="${profile_image}" class="rad10" width="57" height="57"></a></div>
    <div class="row_membername font10 row"><a href="${profile_url}">${name}</a> (${friends_count})</div>
  </div>
</script>
<script type="text/javascript">
$(function(){
  $.getJSON( openpne.apiBase + 'member/search.json?apiKey=' + openpne.apiKey + '&target=friend&target_id=<?php echo $member->getId()?>', function(json) {
    $('#friendListTemplate').tmpl(json.data).appendTo('#memberFriendList');
  });
});
</script>

<div class="row">
  <div class="gadget_header span12"><?php echo __('%1%\'s %friend% List', array('%1%' => $member->getName(), '%friend%' => $op_term['friend']->titleize())); ?></div>
</div>
<hr class="toumei" />
<div class="row" id="memberFriendList">

</div>

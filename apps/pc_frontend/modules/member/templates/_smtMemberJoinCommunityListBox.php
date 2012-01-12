<?php use_helper('Javascript') ?>
<script id="joinCommunityListTemplate" type="text/x-jquery-tmpl">
  <div class="span3">
    <div class="row_memberimg row"><div class="span3 center"><a href="${community_url}"><img src="${community_image_url}" class="rad10" width="57" height="57"></a></div></div>
    <div class="row_membername font10 row"><div class="span3 center"><a href="${community_url}">${name}</a> (${member_count})</div></div>
  </div>
</script>
<script type="text/javascript">
$(function(){
  $.getJSON( openpne.apiBase + 'member/community.json?member_id=<?php echo $member->getId() ?>&apiKey=' + openpne.apiKey, function(json) {
    $('#joinCommunityListTemplate').tmpl(json.data).appendTo('#memberJoinCommunityList');
    $('#memberJoinCommunityList').show();
    $('#memberJoinCommunityListLoading').hide();
  });
});
</script>

<hr class="toumei" />
<div class="row">
  <div class="gadget_header span12"><?php echo __('%community% List', array('%community%' => $op_term['community'])) ?></div>
</div>
<hr class="toumei" />
<div class="row hide" id="memberJoinCommunityList">
</div>
<div class="row" id="memberJoinCommunityListLoading" style="margin-left: 0; text-align: center;">
<?php echo op_image_tag('ajax-loader.gif') ?>
</div>

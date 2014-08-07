<div id="MemberJoinCommunityListBox_<?php echo $gadget->id ?>">

<script id="joinCommunityListTemplate" type="text/x-jquery-tmpl">
  <div class="span3">
    <div class="row_memberimg row"><div class="span3 center"><a href="${community_url}"><img src="${community_image_url}" class="rad10" width="57" height="57"></a></div></div>
    <div class="row_membername font10 row"><div class="span3 center"><a href="${community_url}">${name}</a> (${member_count})</div></div>
  </div>
</script>

<hr class="toumei" />
<div class="row">
  <div class="gadget_header span12"><?php echo __('%community% List', array('%community%' => $op_term['community'])) ?></div>
</div>
<hr class="toumei" />

<?php include_partial('default/smtSearchListBox') ?>
<script type="text/javascript">
<!--
$(function() {
  var baseElm = $('#MemberJoinCommunityListBox_<?php echo $gadget->id ?>');
  var listItemTemplate = $('#joinCommunityListTemplate');
  var endpoint = 'member/community.json';
  var searchParams = { member_id: <?php echo $member->id ?> };

  var searchBox = new opSearchBox(baseElm, listItemTemplate, endpoint, searchParams);
  searchBox.search();
});
//-->
</script>

</div>

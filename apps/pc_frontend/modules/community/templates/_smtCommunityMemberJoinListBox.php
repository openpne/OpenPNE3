<div id="CommunityMemberJoinListBox_<?php echo $gadget->id ?>">

<script id="communityMemberJoinListTemplate" type="text/x-jquery-tmpl">
  <div class="span3">
    <div class="row_memberimg row"><div class="span3 center"><a href="${profile_url}"><img src="${profile_image}" class="rad10" width="57" height="57"></a></div></div>
    <div class="row_membername font10 row"><div class="span3 center"><a href="${profile_url}">${name}</a> (${friends_count})</div></div>
  </div>
</script>

<hr class="toumei" />
<div class="row">
  <div class="gadget_header span12"><?php echo __('%community% Members', array('%community%' => $op_term['community'])) ?></div>
</div>
<hr class="toumei" />

<?php include_partial('default/smtSearchListBox') ?>
<script type="text/javascript">
<!--
$(function() {
  var baseElm = $('#CommunityMemberJoinListBox_<?php echo $gadget->id ?>');
  var listItemTemplate = $('#communityMemberJoinListTemplate');
  var endpoint = 'member/search.json';
  var searchParams = { target: 'community', target_id: <?php echo $community->id ?> };

  var searchBox = new opSearchBox(baseElm, listItemTemplate, endpoint, searchParams);
  searchBox.search();
});
//-->
</script>

</div>

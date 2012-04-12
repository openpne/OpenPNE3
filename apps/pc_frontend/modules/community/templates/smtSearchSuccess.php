<?php use_helper('Javascript') ?>
<script id="joinCommunityListTemplate" type="text/x-jquery-tmpl">
  <div class="span3">
    <div class="row_memberimg row"><div class="span3 center"><a href="${community_url}"><img src="${community_image_url}" class="rad10" width="57" height="57"></a></div></div>
    <div class="row_membername font10 row"><div class="span3 center"><a href="${community_url}">${name}</a> (${member_count})</div></div>
  </div>
</script>
<script type="text/javascript">
$(function(){
  $.getJSON( openpne.apiBase + 'community/search.json?apiKey=' + openpne.apiKey, function(json) {
    if (json.data.length > 0) {
      $('#joinCommunityListTemplate').tmpl(json.data).appendTo('#memberJoinCommunityList');
      $('#memberJoinCommunityList').show();
      $('#joinCommunitySearch').removeAttr('disabled');
    } else {
      $('#memberJoinCommunityNotExist').show();
    }
    $('#memberJoinCommunityListLoading').hide();
  });
  $('#joinCommunitySearch').keypress(function(){
    $('#memberJoinCommunityListLoading').show();
    $('#memberJoinCommunityList, #memberJoinCommunityNotMatch').hide();
    $('#memberJoinCommunityList').empty();
  });
  $('#joinCommunitySearch').blur(function(){
    var keyword = $('#joinCommunitySearch').val();
    var requestData = { keyword: keyword, apiKey: openpne.apiKey };
    $.getJSON( openpne.apiBase + 'community/search.json', requestData, function(json) {
      if (json.data.length > 0) {
        $result = $('#joinCommunityListTemplate').tmpl(json.data);
        $('#memberJoinCommunityList').html($result);
        $('#memberJoinCommunityList').show();
      } else {
        $('#memberJoinCommunityNotMatch').show();
      }
      $('#memberJoinCommunityListLoading').hide();
    });
  });
});
</script>

<hr class="toumei" />
<div class="row">
  <div class="gadget_header span12"><?php echo __('Search %community%', array('%community%' => $op_term['community']->titleize()->pluralize())) ?></div>
</div>
<hr class="toumei" />
<div class="row" id="joinCommunitySearchBox">
<div class="input-prepend span12">
<span class="add-on"><i class="icon-search"></i></span>
<input type="text" id="joinCommunitySearch" class="realtime-searchbox" value="" disabled="disabled" />
</div>
</div>
<div class="row hide" id="memberJoinCommunityList">
</div>
<div class="row hide" id="memberJoinCommunityNotMatch">
<?php echo __('Your search queries did not match any %community%.') ?>
</div>
<div class="row hide" id="memberJoinCommunityNotExist">
<?php echo __('%Community% does not exist.') ?>
</div>
<div class="row" id="memberJoinCommunityListLoading" style="margin-left: 0; text-align: center;">
<?php echo op_image_tag('ajax-loader.gif') ?>
</div>

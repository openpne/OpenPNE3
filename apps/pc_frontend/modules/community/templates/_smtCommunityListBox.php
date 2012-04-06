<div class="row">
  <div class="gadget_header span12">コミュニティ情報</div>
</div>
<div class="row">
  <div class="span12">
    <hr class="toumei" />
    <?php echo op_image_tag_sf_image($community->getImageFileName(), array('size' => '320x320', 'format' => 'jpg')) ?>
    <hr class="toumei" />
  </div>
</div>
<div class="row">
<table class="table table-striped span12">
<tbody>
<tr>
  <td><?php echo __('Date Created') ?></td>
  <td><?php echo op_format_date($community->getCreatedAt(), 'D') ?></td>
</tr>
<tr>
  <td><?php echo __('Administrator') ?></td>
  <td><?php echo link_to($communityAdmin->getName(), '@member_profile?id='.$communityAdmin->getId()) ?></td>
</tr>
<?php
$subAdminCaption = array();
foreach ($communitySubAdmins as $m) 
{
  $subAdminCaption[] = link_to($m->getName(), '@member_profile?id='.$m->getId());
}
?>
<?php if (count($subAdminCaption)): ?>
<tr>
  <td><?php echo __('Sub Administrator') ?></td>
  <td><?php echo implode("<br />\n", $subAdminCaption) ?></td>
</tr>
<?php endif; ?>
<?php if ($community->community_category_id): ?>
<tr>
  <td><?php echo __('%community% Category', array('%community%' => $op_term['community']->titleize()), 'form_community') ?>:</td>
  <td><?php echo $community->getCommunityCategory() ?></td>
</tr>
<?php endif; ?>
<tr>
  <td><?php echo __('Register policy', array('%community%' => $op_term['community']->titleize()), 'form_community') ?>:</td>
  <td><?php echo __($sf_data->getRaw('community')->getRegisterPolicy()) ?></td>
</tr>
<tr>
  <td><?php echo __('Count of Members'); ?></td>
  <td><?php echo $community->countCommunityMembers(); ?></td>
</tr>
<tr>
  <td><?php echo __('%community% Description', array('%community%' => $op_term['community']->titleize()), 'form_community') ?></td>
  <td><?php echo nl2br($community->getConfig('description')) ?></td>
</tr>
<tr>
  <td></td>
  <td>
  <?php if ($isEditCommunity) : ?>
  <?php endif; ?>
  <?php if (!$isAdmin) : ?>
  <?php if ($isCommunityMember) : ?>
  <p id="leaveCommunityLink"><a href="#" id="leaveCommunity"><?php echo __('Leave this %community%', array('%community%' => $op_term['community']->titleize())) ?></a></p>
  <p id="leaveCommunityLoading" class="hide"><?php echo op_image_tag('ajax-loader.gif') ?></p>
  <p id="leaveCommunityFinish" class="hide"><?php echo __('You have just quitted this %community%.') ?></p>
  <p id="leaveCommunityError" class="hide"><?php echo __('You haven\'t joined this %community% yet.') ?></p>
  <?php else : ?>
  <?php if ($isCommunityPreMember) : ?>
  <?php echo __('You are waiting for the participation approval by %community%\'s administrator.', array('%community%' => $op_term['community']->titleize())) ?>
  <?php else: ?>
  <p id="joinCommunityLink"><a href="#" id="joinCommunity"><?php echo __('Join this %community%', array('%community%' => $op_term['community']->titleize())) ?></a></p>
  <p id="joinCommunityLoading" class="hide"><?php echo op_image_tag('ajax-loader.gif') ?></p>
  <p id="joinCommunityFinish" class="hide"><?php echo __('You have just joined to this %community%.') ?></p>
  <p id="joinCommunityError" class="hide"><?php echo __('You are already joined to this %community%.') ?></p>
  <?php endif; ?>
  <?php endif; ?>
  <?php endif; ?>
  </td></tr>
</tbody>
</table>
</div>
<script type="text/javascript">
$(function(){

  $('#leaveCommunity').click(function(){
    $('#leaveCommunityLoading').show();
    $('#leaveCommunityLink').hide();
    $.ajax({
      type: 'GET',
      url: openpne.apiBase + 'community/join.json',
      data: 'community_id=<?php echo $community->getId() ?>&leave=true&apiKey=' + openpne.apiKey,
      success: function(json){
        $('#leaveCommunityFinish').show();
        $('#leaveCommunityLoading').hide();
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){
        $('#leaveCommunityError').show();
        $('#leaveCommunityLoading').hide();
      },
    });
  });

  $('#joinCommunity').click(function(){
    $('#joinCommunityLoading').show();
    $('#joinCommunityLink').hide();
    $.ajax({
      type: 'GET',
      url: openpne.apiBase + 'community/join.json',
      data: 'community_id=<?php echo $community->getId() ?>&apiKey=' + openpne.apiKey,
      success: function(json){
        $('#joinCommunityFinish').show();
        $('#joinCommunityLoading').hide();
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){
        $('#joinCommunityError').show();
        $('#joinCommunityLoading').hide();
      },
    });
  });

});
</script>

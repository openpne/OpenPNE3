<div class="row">
  <div class="gadget_header span12">コミュニティ情報</div>
</div>
<div class="row">
  <div class="span12">
    <hr class="toumei" />
    <?php echo op_image_tag_sf_image($community->getImageFileName(), array('size' => '320x320', 'format' => 'jpg')) ?>
    <hr class="toumei" />
    <?php if ($isAdmin) : ?>
    <?php echo link_to(__('Edit Photo'), 'community/configImage?id='.$community->getId()) ?>
    <?php endif; ?>
  </div>
</div>
<div class="row">
<table class="zebra-striped">
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
  <td><?php echo implode("<br>\n", $subAdminCaption) ?></td>
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
  <td><?php echo $community->getConfig('description'); ?></td>
</tr>
<tr>
  <td></td>
  <td>
  <?php if ($isEditCommunity) : ?>
  <?php echo link_to(__('Edit this %community%', array('%community%' => $op_term['community']->titleize())), '@community_edit?id=' . $community->getId()) ?><br>
  <?php endif; ?>
  <?php if (!$isAdmin) : ?>
  <?php if ($isCommunityMember) : ?>
  <?php echo link_to(__('Quit this %community%', array('%community%' => $op_term['community']->titleize())), '@community_quit?id=' . $community->getId()) ?><br>
  <?php else : ?>
  <?php if ($isCommunityPreMember) : ?>
  <?php echo __('You are waiting for the participation approval by %community%\'s administrator.', array('%community%' => $op_term['community']->titleize())) ?>
  <?php else: ?>
  <?php echo link_to(__('Join this %community%', array('%community%' => $op_term['community']->titleize())), '@community_join?id=' . $community->getId()) ?><br>
  <?php endif; ?>
  <?php endif; ?>
  <?php endif; ?>
  </td></tr>
</tbody>
</table>
</div>

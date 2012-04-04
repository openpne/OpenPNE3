<div class="row">
  <div class="gadget_header span12"><?php echo __('%community% List', array('%community%' => $op_term['community']->titleize())); ?></div>
</div>
<hr class="toumei" />
<div class="row" style="margin-left: 0px;">
<?php foreach ( $pager->getResults() as $member ): ?>
<div class="span3">
  <div class="row_memberimg row">
<?php echo op_link_to_member($member, array('link_target' => op_image_tag_sf_image($member->getImageFileName(), array('size' => '57x57')))) ?>
  </div>
  <div class="row_membername font10 row"><?php echo link_to($member->getName(), '@member_profile?id='.$member->getId()); ?></div>
</div>
<?php endforeach ?>
</div>

<div class="row" style="text-align: right;">
<?php echo link_to(__('Back to %member% profile page', array('%member%' => $targetMember->getName())), '@member_profile?id='.$targetMember->getId()) ?>
</div>

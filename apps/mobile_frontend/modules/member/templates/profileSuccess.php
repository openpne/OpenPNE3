<?php op_mobile_page_title($member->getName()) ?>

<?php if ($member == $sf_user->getMember()) : ?>
<font color="<?php echo $op_color["core_color_22"] ?>">
<?php echo __('This is your page other member see.') ?><br>
<?php echo __('If you edit profile, access %1%.', array('%1%' => link_to('「'. __('Edit profile') .'」', 'member/editProfile'))) ?>
</font>
<?php else: ?>
<?php include_partial('member/birthdayBox', array('targetDay' => $targetDay)); ?>
<?php endif; ?>

<?php if ($mobileTopGadgets) : ?>
<?php foreach ($mobileTopGadgets as $gadget) : ?>
<?php if ($gadget->isEnabled()) : ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<table width="100%" bgcolor="<?php echo $op_color["core_color_4"] ?>">
<tr><td colspan="2" align="center">
<?php include_customizes('menu', 'top') ?>
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
</td></tr>

<tr><td align="center" width="50%" valign="top">
<?php echo image_tag_sf_image($member->getImageFileName(), array('size' => '120x120', 'format' => 'jpg')) ?>
<?php if ($relation->isSelf()) : ?>
<br><?php echo link_to(__('Edit Photo'), 'member/configImage') ?>
<?php elseif ($member->getImageFileName()) : ?>
<br><?php echo link_to(__('Show Photo'), 'friend/showImage?id='.$member->getId()) ?>
<?php endif; ?>
</td>
<td valign="top">
<?php foreach ($member->getProfiles(true) as $profile) : ?>
<font color="<?php echo $op_color["core_color_19"] ?>"><?php echo $profile->getCaption() ?>:</font><br>
<?php echo $profile ?><br>
<?php if ($member->getId() == $sf_user->getMemberId() && $profile->getPublicFlag() == ProfileTable::PUBLIC_FLAG_FRIEND): ?>
<font color="<?php echo $op_color["core_color_22"] ?>">(<?php echo __('Only Open to My Friends') ?>)</font><br>
<?php endif; ?>
<?php endforeach; ?>
</td>
</tr>

<tr><td colspan="2" align="center">
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
</td></tr>

<tr><td colspan="2">

<?php if (opConfig::get('enable_friend_link') && !$relation->isFriend() && !$relation->isSelf()) : ?>
<?php echo link_to(__('Makes friends'), 'friend/link?id='.$member->getId()) ?><br>
<?php endif; ?>

<?php include_customizes('menu', 'friendTop') ?>
<?php include_component('default', 'nav', array('type' => 'mobile_friend', 'id' => $member->getId())) ?>
<?php include_customizes('menu', 'friendBottom') ?>

<?php include_customizes('menu', 'bottom') ?>
</td></tr>

<tr><td colspan="2" align="center">
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
</td></tr>
</table>
<br>
<?php if ($mobileContentsGadgets) : ?>
<?php foreach ($mobileContentsGadgets as $gadget) : ?>
<?php if ($gadget->isEnabled()) : ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<br>

<?php if ($mobileBottomGadgets) : ?>
<?php foreach ($mobileBottomGadgets as $gadget) : ?>
<?php if ($gadget->isEnabled()) : ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<?php slot('op_mobile_footer') ?>
<table width="100%">
<tbody><tr><td align="center" bgcolor="<?php echo $op_color["core_color_2"] ?>">
<font color="<?php echo $op_color["core_color_18"] ?>"><a href="<?php echo url_for('member/home') ?>" accesskey="0"><font color="<?php echo $op_color["core_color_18"] ?>">0. <?php echo __('home') ?></font></a> / <a href="#top"><font color="<?php echo $op_color["core_color_18"] ?>"><?php echo __('top') ?></font></a> / <a href="#bottom" accesskey="8"><font color="<?php echo $op_color["core_color_18"] ?>">8. <?php echo __('bottom') ?></font></a></font><br>
</td></tr></tbody></table>
<?php end_slot(); ?>

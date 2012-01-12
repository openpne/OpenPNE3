<?php if ($relation->isSelf()): ?>
<?php ob_start() ?>
<div class="alert-message block-message info">
<p><?php echo __('Other members look your page like this.') ?></p>
<p><?php echo link_to(__('Edit profile'), '@member_editProfile') ?></p>
</div>
<?php $content = ob_get_clean() ?>
<?php op_include_parts('descriptionBox', 'informationAboutThisIsYourProfilePage', array('body' => $content)) ?>
<?php else: ?>
<?php if (!$relation->isFriend() && opConfig::get('enable_friend_link') && $relation->isAllowed($sf_user->getRawValue()->getMember(), 'friend_link')): ?>
<?php ob_start() ?>
<div class="alert-message block-message warning">
<p><?php echo __('If %1% is your friend, let us add to %my_friend% it!', array('%1%' => $member->getName(), '%my_friend%' => $op_term['my_friend']->pluralize())) ?><br />
<?php echo link_to(__('Add %my_friend%', array('%my_friend%' => $op_term['my_friend']->pluralize())), 'friend/link?id='.$member->getId()) ?>
</p>
</div>
<?php $content = ob_get_clean() ?>
<?php op_include_parts('descriptionBox', 'informationAboutThisIsYourProfilePage', array('body' => $content)) ?>
<?php endif; ?>
<?php endif; ?>

<?php if (isset($contentsGadgets)): ?>
<?php foreach ($contentsGadgets as $gadget): ?>
<?php if ($gadget->isEnabled()): ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

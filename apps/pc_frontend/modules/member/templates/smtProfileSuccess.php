<?php if ($relation->isSelf()): ?>
<?php ob_start() ?>
<div class="alert alert-info">
<p><?php echo __('Other members look your page like this.') ?></p>
<p><?php echo link_to(__('Edit profile'), '@member_editProfile') ?></p>
</div>
<?php $content = ob_get_clean() ?>
<?php op_include_parts('descriptionBox', 'informationAboutThisIsYourProfilePage', array('body' => $content)) ?>
<?php else: ?>
<?php if (!$relation->isFriend() && opConfig::get('enable_friend_link') && $relation->isAllowed($sf_user->getRawValue()->getMember(), 'friend_link')): ?>
<?php ob_start() ?>
<script type="text/javascript">
$(function(){
  $('#addFriend').click(function(){
    $('#addFriendLinkLoading').show();
    $('#addFriendLink').hide();
    $.ajax({
      type: 'GET',
      url: openpne.apiBase + 'member/friend_request.json',
      data: 'member_id=<?php echo $member->getId() ?>&apiKey=' + openpne.apiKey,
      success: function(json){
        $('#addFriendLinkFinish').show();
        $('#addFriendLinkLoading').hide();
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){
        $('#addFriendLinkError').show();
        $('#addFriendLinkLoading').hide();
      },
    });
  });
});
</script>
<div class="alert alert-warning">
<p><?php echo __('If %1% is your friend, let us add to %my_friend% it!', array('%1%' => $member->getName(), '%my_friend%' => $op_term['my_friend']->pluralize())) ?></p>
<p id="addFriendLink"><a href="#" id="addFriend"><?php echo __('Add %my_friend%', array('%my_friend%' => $op_term['my_friend']->pluralize())) ?></a></p>
<p id="addFriendLinkLoading" class="hide"><?php echo op_image_tag('ajax-loader.gif') ?></p>
<p id="addFriendLinkFinish" class="hide"><?php echo __('You have requested %friend% link.', array('%friend%' => $op_term['friend'])) ?></p>
<p id="addFriendLinkError" class="hide"><?php echo __('%Friend% request is already sent.', array('%Friend%' => $op_term['friend'])) ?></p>
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

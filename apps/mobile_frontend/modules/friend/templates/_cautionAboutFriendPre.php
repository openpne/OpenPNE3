<?php $form = new sfForm() ?>
<?php foreach ($sf_user->getMember()->getFriendPreTo() as $key => $value) : ?>
<font color="red">
<?php $member = $value->getMemberRelatedByMemberIdFrom(); ?>
<?php echo __('Received from the friend link message from %1%!', array('%1%' => link_to(sprintf('%s', $member->getName()), 'member/profile?id='.$member->getId()))) ?>
&nbsp;
<?php $param = 'id='.$member->getId() ?>
<?php $param .= '&'.$form->getCSRFFieldName().'='.$form->getCSRFToken() ?>
<?php echo link_to(__('Accept'), 'friend/linkAccept?'.$param) ?>
&nbsp;
<?php echo link_to(__('Reject'), 'friend/linkReject?'.$param) ?>
</font><br>
<?php endforeach; ?>

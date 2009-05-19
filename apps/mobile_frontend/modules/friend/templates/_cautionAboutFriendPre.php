<?php foreach ($sf_user->getMember()->getFriendPreTo() as $key => $value) : ?>
<font color="red">
<?php $member = $value->getMemberRelatedByMemberIdFrom(); ?>
<?php echo __('Received from the friend link message from %1%!', array('%1%' => link_to(sprintf('%s', $member->getName()), 'member/profile?id='.$member->getId()))) ?>
&nbsp;
<?php echo link_to(__('Accept'), 'friend/linkAccept?id='.$member->getId()) ?>
&nbsp;
<?php echo link_to(__('Reject'), 'friend/linkReject?id='.$member->getId()) ?>
</font><br>
<?php endforeach; ?>

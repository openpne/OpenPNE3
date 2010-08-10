<?php $form = new sfForm() ?>
<?php foreach ($sf_user->getMember()->getFriendPreTo() as $key => $value) : ?>
<p class="caution">
<?php
$member = $value->getMemberRelatedByMemberIdFrom();
echo __('%1% sent my friends request to you!', array('%1%' => link_to($member->getName(), 'member/profile?id='.$member->getId()))) ?>
&nbsp;
<?php $param = 'id='.$member->getId() ?>
<?php $param .= '&'.$form->getCSRFFieldName().'='.$form->getCSRFToken() ?>
<?php echo link_to(__('Permits'), 'friend/linkAccept?'.$param) ?>
&nbsp;
<?php echo link_to(__('Refuses'), 'friend/linkReject?'.$param) ?>
</p>
<?php endforeach; ?>

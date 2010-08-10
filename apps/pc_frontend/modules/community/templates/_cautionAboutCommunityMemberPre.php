<?php $form = new sfForm() ?>
<?php foreach ($communityMembers as $communityMember) : ?>
<p class="caution">
<?php echo __('%1% send request for participation to %2%.', array(
'%1%' => link_to($communityMember->getMember()->getName(), 'member/profile?id='.$communityMember->getMemberId()),
'%2%' => link_to($communityMember->getCommunity()->getName(), 'community/home?id='.$communityMember->getCommunityId())
)) ?>
&nbsp;
<?php $param = 'id='.$communityMember->getCommunityId().'&member_id='.$communityMember->getMemberId(); ?>
<?php $param .= '&'.$form->getCSRFFieldName().'='.$form->getCSRFToken() ?>
<?php echo link_to(__('Permits'), 'community/joinAccept?'.$param) ?>
&nbsp;
<?php echo link_to(__('Refuses'), 'community/joinReject?'.$param) ?>
</p>
<?php endforeach; ?>

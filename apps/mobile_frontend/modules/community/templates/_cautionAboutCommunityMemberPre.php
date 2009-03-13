<?php foreach ($communityMembers as $communityMember) : ?>
<font color="red">
<?php echo __('%1% send request for participation to %2%.', array(
'%1%' => link_to($communityMember->getMember()->getName(), 'member/profile?id='.$communityMember->getMemberId()),
'%2%' => link_to($communityMember->getCommunity()->getName(), 'community/home?id='.$communityMember->getCommunityId())
)) ?>
&nbsp;
<?php $param = 'id='.$communityMember->getCommunityId().'&member_id='.$communityMember->getMemberId(); ?>
<?php echo link_to(__('Accept'), 'community/joinAccept?'.$param) ?>
&nbsp;
<?php echo link_to(__('Reject'), 'community/joinReject?'.$param) ?>
</font><br>
<?php endforeach; ?>

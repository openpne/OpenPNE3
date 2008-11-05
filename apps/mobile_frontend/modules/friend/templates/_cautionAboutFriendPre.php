<?php foreach ($sf_user->getMember()->getFriendPresRelatedByMemberIdTo() as $key => $value) : ?>
<font color="red">
<?php
$member = $value->getMemberRelatedByMemberIdFrom();
echo link_to(sprintf('%sさん', $member->getName()), 'member/profile?id='.$member->getId()) ?>
からﾌﾚﾝﾄﾞ申請がきています！
&nbsp;
<?php echo link_to('許可する', 'friend/linkAccept?id='.$member->getId()) ?>
&nbsp;
<?php echo link_to('拒否する', 'friend/linkReject?id='.$member->getId()) ?>
</font><br>
<?php endforeach; ?>

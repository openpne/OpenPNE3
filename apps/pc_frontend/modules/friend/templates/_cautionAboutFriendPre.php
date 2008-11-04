<?php foreach ($sf_user->getMember()->getFriendPresRelatedByMemberIdTo() as $key => $value) : ?>
<p class="caution">
<?php
$member = $value->getMemberRelatedByMemberIdFrom();
echo link_to(sprintf('%sさん', $member->getName()), 'member/profile?id='.$member->getId()) ?>
からフレンド申請がきています！
&nbsp;
<?php echo link_to('許可する', 'friend/linkAccept?id='.$member->getId()) ?>
&nbsp;
<?php echo link_to('拒否する', 'friend/linkReject?id='.$member->getId()) ?>
</p>
<?php endforeach; ?>

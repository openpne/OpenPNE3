<?php echo $member->getProfile('nickname') ?> さんのホームです。
<ul>
<?php if ($sf_user->hasCredential('friend')): ?>
<li><?php echo link_to('フレンドをやめる', 'friend/unlink?id=' . $member->getId()) ?></li>
<?php else: ?>
<li><?php echo link_to('フレンドになる', 'friend/link?id=' . $member->getId()) ?></li>
<?php endif; ?>
<li><?php echo link_to(sprintf('フレンド一覧(%d)', $member->countFriendsRelatedByMemberIdTo()), 'friend/list?member_id=' . $member->getId()) ?></li>
<li><?php echo link_to(sprintf('参加コミュニティ一覧(%d)', $member->countCommunityMembers()), 'community/joinlist?member_id=' . $member->getId()) ?></li>
</ul>

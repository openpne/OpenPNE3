<dl>
<dt>ニックネーム</dt>
<dd><?php echo $member->getName() ?></dd>
<?php foreach ($member->getProfiles() as $profile) : ?>
<dt><?php echo $profile->getCaption() ?></dt>
<dd><?php echo $profile ?></dd>
<?php endforeach; ?>
</dl>

<ul>
<li><?php echo link_to(sprintf('フレンド一覧(%d)', $member->countFriendsRelatedByMemberIdTo()), 'friend/list?id=' . $member->getId()) ?></li>
<li><?php echo link_to(sprintf('参加コミュニティ一覧(%d)', $member->countCommunityMembers()), 'community/joinlist?member_id=' . $member->getId()) ?></li>
</ul>

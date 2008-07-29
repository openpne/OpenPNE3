あなたのホームです。(メンバーID:<?php echo $sf_user->getMemberId() ?>, ニックネーム:<?php echo $sf_user->getMember()->getProfile('nickname') ?>)
<ul>
<li><?php echo link_to('プロフィール確認', 'member/profile') ?></li>
<li><?php echo link_to('メンバー一覧', 'member/list') ?></li>
<li><?php echo link_to('コミュニティ追加', 'community/edit') ?></li>
<li><?php echo link_to('コミュニティ一覧', 'community/list') ?></li>
<li><?php echo link_to(sprintf('フレンド一覧(%d)', $sf_user->getMember()->countFriendsRelatedByMemberIdTo()), 'friend/list') ?></li>
<li><?php echo link_to(sprintf('参加コミュニティ一覧(%d)', $sf_user->getMember()->countCommunityMembers()), 'community/joinlist') ?></li>
<li><?php echo link_to('ログアウト', 'member/logout') ?></li>
</ul>

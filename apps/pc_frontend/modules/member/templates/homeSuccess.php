あなたのホームです。(メンバーID:<?php echo $sf_user->getMemberId() ?>, ニックネーム:<?php echo $sf_user->getProfile('nickname') ?>)
<ul>
<li><?php echo link_to('メンバー一覧', 'member/list') ?></li>
<li><?php echo link_to('コミュニティ追加', 'community/edit') ?></li>
<li><?php echo link_to('コミュニティ一覧', 'community/list') ?></li>
</ul>

あなたのホームです。(メンバーID:<?php echo $sf_user->getMemberId() ?>, ニックネーム:<?php echo $sf_user->getProfile('nickname') ?>)
<ul>
<li><?php echo link_to('メンバー一覧', 'member/list') ?></li>
</ul>

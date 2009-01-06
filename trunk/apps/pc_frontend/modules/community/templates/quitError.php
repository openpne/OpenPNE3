<?php if ($isAdmin) : ?>
<?php include_box('admin', 'エラー', '管理者は退会できません。'); ?>
<?php else: ?>
<?php include_box('nonAdmin', 'エラー', 'まだコミュニティに参加していません。'); ?>
<?php endif; ?>

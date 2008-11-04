<?php if ($isFriend) : ?>
<?php include_box('alreadyFriend', 'エラー', '既にフレンドです。'); ?>
<?php elseif ($isFriendPre) : ?>
<?php include_box('alreadyFriend', 'エラー', '現在フレンド申請中です。'); ?>
<?php endif; ?>

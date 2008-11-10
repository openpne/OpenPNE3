<?php if ($relation->isFriend()) : ?>
<?php include_box('alreadyFriend', 'エラー', '既にフレンドです。'); ?>
<?php elseif ($relation->isFriendPre()) : ?>
<?php include_box('alreadyFriend', 'エラー', '現在フレンド申請中です。'); ?>
<?php endif; ?>

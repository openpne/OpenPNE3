<?php if ($relation->isFriend()): ?>
<?php op_include_box('alreadyFriend', __('既にフレンドです。'), array('title' => __('エラー'))) ?>
<?php elseif ($relation->isFriendPre()): ?>
<?php op_include_box('alreadyFriend', __('現在フレンド申請中です。'), array('title' => __('エラー'))) ?>
<?php endif; ?>

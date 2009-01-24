<?php slot('submenu'); ?>
<?php include_partial('submenu'); ?>
<?php end_slot(); ?>

<h2><?php echo __('招待メール送信') ?></h2>

<p><?php echo __('招待可能な認証プラグインがありません。') ?></p>
<p><?php echo __('「プラグイン設定」から認証プラグインの設定を変更してからやりなおしてください。') ?></p>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('前のページに戻る'), 'history.back()') ?></p>

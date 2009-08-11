<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<h2><?php echo __('アクセストークン削除確認') ?></h2>

<p><?php echo $consumer->name ?> のアクセストークンを削除しますか？</p>
<p>トークンを削除すると、再度承認をおこなわない限り、 <?php echo $consumer->name ?> はリソースへのアクセスをおこなえなくなります。</p>

<?php echo $form->renderFormTag(url_for('connection/removeToken?id='.$consumer->id)) ?>
<?php echo $form ?>
<input type="submit" value="<?php echo __('Delete'); ?>" />
</form>

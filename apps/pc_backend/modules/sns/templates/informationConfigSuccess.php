<?php slot('submenu') ?>
<li>お知らせ設定</li>
<li>ナビ設定</li>
<?php end_slot() ?>
<h2><?php echo __('お知らせ設定') ?></h2>

<ul class="contents_menu">
  <li><?php echo link_to('PC版ホームのお知らせ', 'sns/informationConfig?target=pc_home') ?></li>
  <li><?php echo link_to('携帯版ホームのお知らせ', 'sns/informationConfig?target=mobile_home') ?></li>
</ul>

<?php if ($target === 'pc_home') : ?>
<h3>PC版ホームのお知らせ</h3>
<?php elseif ($target === 'mobile_home') : ?>
<h3>携帯版ホームのお知らせ</h3>
<?php endif; ?>

<form action="<?php echo url_for('sns/informationConfig') ?>" method="post">
<table>
<?php echo $form['target']->render() ?>
<?php echo $form['information']->render() ?>
<tr>
<td colspan="2"><input type="submit" value="設定変更する" /></td>
</tr>
</table>
</form>

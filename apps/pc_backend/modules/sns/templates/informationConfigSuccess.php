<ul>
  <li><?php echo link_to('PC版ホームのお知らせ', 'sns/informationConfig?target=pc_home') ?></li>
  <li><?php echo link_to('携帯版ホームのお知らせ', 'sns/informationConfig?target=mobile_home') ?></li>
</ul>

<?php if ($target === 'pc_home') : ?>
<h2>PC版ホームのお知らせ</h2>
<?php elseif ($target === 'mobile_home') : ?>
<h2>携帯版ホームのお知らせ</h2>
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

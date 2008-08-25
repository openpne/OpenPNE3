<h2>ナビ設定</h2>

<h3>ログイン前メニュー</h3>
<table>
<tr>
<th>URL</th>
<th>項目名(ja_JP)</th>
<th colspan="2">操作</th>
</tr>
<?php foreach ($insecureGlobal as $form) : ?>
<tr>
<td><form action="<?php echo url_for('navi/edit') ?>" method="post">
<?php echo $form['uri']->render() ?></td>
<td><?php echo $form['ja_JP']['caption']->render() ?><?php echo $form['type']->render(array('value' => 'insecure_global')) ?></td>
<?php if ($form->isNew()) : ?>
<td colspan="2"><input type="submit" value="追加" /></form></td>
<?php else : ?>
<td><?php echo $form['id']->render() ?>
<input type="submit" value="編集" /></form></td>
<td><form action="<?php echo url_for('navi/delete?id=' . $form->getObject()->getId()) ?>" method="post" /><input type="submit" value="削除" /></form></td>
<?php endif; ?>
</tr>
<?php endforeach; ?>
</table>

<h3>ログイン後メニュー</h3>
<table>
<tr>
<th>URL</th>
<th>項目名(ja_JP)</th>
<th colspan="2">操作</th>
</tr>
<?php foreach ($secureGlobal as $form) : ?>
<tr>
<td><form action="<?php echo url_for('navi/edit') ?>" method="post">
<?php echo $form['uri']->render() ?></td>
<td><?php echo $form['ja_JP']['caption']->render() ?><?php echo $form['type']->render(array('value' => 'secure_global')) ?></td>
<?php if ($form->isNew()) : ?>
<td colspan="2"><input type="submit" value="追加" /></form></td>
<?php else : ?>
<td><?php echo $form['id']->render() ?>
<input type="submit" value="編集" /></form></td>
<td><form action="<?php echo url_for('navi/delete?id=' . $form->getObject()->getId()) ?>" method="post" /><input type="submit" value="削除" /></form></td>
<?php endif; ?>
</tr>
<?php endforeach; ?>
</table>

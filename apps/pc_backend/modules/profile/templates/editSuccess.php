<h2>プロフィール項目追加</h2>
<?php if ($form->isNew()): ?>
<form action="<?php echo url_for('profile/edit') ?>" method="post">
<?php else : ?>
<form action="<?php echo url_for('profile/edit?id=' . $profile->getId()) ?>" method="post">
<?php echo $form['id']->render() ?>
<?php endif; ?>
<table>

<tr><th colspan="2">ja_JP 用設定</th></tr>
<?php echo $form['ja_JP']['caption']->renderRow() ?>
<?php echo $form['ja_JP']['info']->renderRow() ?>

<tr><th colspan="2">共通設定</th></tr>
<?php echo $form['name']->renderRow() ?>
<?php echo $form['is_required']->renderRow() ?>
<?php echo $form['is_unique']->renderRow() ?>
<?php echo $form['sort_order']->renderRow() ?>
<?php echo $form['is_disp_regist']->renderRow() ?>
<?php echo $form['is_disp_config']->renderRow() ?>
<?php echo $form['is_disp_search']->renderRow() ?>
<?php echo $form['form_type']->renderRow() ?>

<tr><th colspan="2">以下の項目はフォームタイプが「テキスト」、「テキスト(複数行)」の場合のみ有効です。</th></tr>
<?php echo $form['value_type']->renderRow() ?>
<tr>
<th><?php echo $form['value_min']->renderLabel() ?>〜<?php echo $form['value_max']->renderLabel() ?></th>
<td><?php echo $form['value_min']->render() ?>〜<?php echo $form['value_max']->render() ?></td>
</tr>
<?php echo $form['value_regexp']->renderRow() ?>

<tr>
<td colspan="2"><input type="submit" value="追加する" /></td>
</tr>
</table>
</form>

<h2><?php echo __('SNS内名称設定') ?></h2>

<p><?php echo __('※「設定変更」ボタンを押すと設定が反映されます。') ?></p>

<form action="" method="post">
<?php echo $form->renderHiddenFields() ?>
<table>
<?php foreach ($sf_data->getRaw('wordList') as $wordConfigs): ?>
<?php foreach ($wordConfigs as $key => $wordConfig): ?>
<?php $field = $form[$wordConfig->getName()] ?>
<tr>
<?php if (!$key): ?>
<th rowspan="<?php echo count($wordConfigs) ?>"><?php echo $wordConfig->getWordType() ?></th>
<?php endif ?>
<th><?php echo $field->renderLabel() ?></th>
<td>
<span class="info"><?php echo $wordConfig->getDescription() ?></span>
<br />
<?php echo $field->renderError() ?>
<?php echo $field->render(array('value' => $wordConfig->getValue())) ?>
</td>
</tr>
<?php endforeach ?>
<?php endforeach ?>

<tr>
<td colspan="3"><input type="submit" value="<?php echo __('設定変更') ?>" /></td>
</tr>

</table>

</form>

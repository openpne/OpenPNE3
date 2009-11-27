<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<h2><?php echo __('カスタム CSS 設定') ?></h2>

<p><?php echo __('標準設定されているスタイルは、ここで上書きすることもできます。') ?></p>

<?php echo $form->renderFormTag(url_for('design/customCss')) ?>
<table>
<tr><td>
<?php echo $form['css']->render() ?>
</td></tr>
<tr><td>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" name="<?php echo __('Save') ?>" />
</td></tr>
</table>
</form>

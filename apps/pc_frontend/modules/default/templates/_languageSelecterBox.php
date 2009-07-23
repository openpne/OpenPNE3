<?php echo $form->renderFormTag(url_for('member/changeLanguage')) ?>
<?php echo $form['culture']->renderLabel() ?>:
<?php echo $form['culture']->render(array('onchange' => 'submit(this.form)')) ?>
<?php echo $form->renderHiddenFields() ?>
</form>

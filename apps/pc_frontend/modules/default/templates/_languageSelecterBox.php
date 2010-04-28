<?php echo $form->renderFormTag(url_for('@global_changeLanguage')) ?>
<?php echo $form['culture']->renderLabel() ?>:
<?php echo $form['culture']->render(array('onchange' => 'submit(this.form)')) ?>
<?php echo $form->renderHiddenFields() ?>
</form>

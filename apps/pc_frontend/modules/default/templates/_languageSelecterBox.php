<form action="<?php echo url_for('member/changeLanguage') ?>" method="post">
<?php echo $form['culture']->renderLabel() ?>
<?php echo $form['culture']->render(array('onchange' => 'submit(this.form)')) ?>
<?php echo $form->renderHiddenFields() ?>
</form>

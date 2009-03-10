<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<h2><?php echo __('パスワード再発行') ?></h2>
<p><?php echo __('%1%さんのパスワードを変更します。', array('%1%' => link_to($member->getName(), app_url_for('pc_frontend', 'member/profile?id='.$member->getId())))) ?></p>

<?php echo $form->renderFormTag(url_for('member/reissuePassword?id='.$member->getId())) ?>
<table>
<?php echo $form ?>
<tr><td colspan="2"><input type="submit" value="<?php echo __('パスワード変更') ?>" /></td></tr>
</table>
</form>

<?php use_helper('Javascript') ?>
<?php echo link_to_function(__('前のページに戻る'), 'history.back()') ?>

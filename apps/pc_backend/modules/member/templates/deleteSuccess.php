<?php slot('submenu'); ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('強制退会') ?></h2>

<p><?php echo __('本当にこのメンバーを強制退会させてもよろしいですか？') ?></p>
<p><?php echo __('強制退会させると、このメンバーに関する情報は削除され元に戻すことはできません。') ?></p>

<form action="<?php echo url_for('member/delete?id='.$member->getId()) ?>" method="post">
<table>
<tr>
<th><?php echo __('ID') ?></th><td><?php echo $member->getId() ?></td>
</tr>
<tr>
<th><?php echo __('ニックネーム') ?></th><td><?php echo $member->getName() ?></td>
</tr>
<?php foreach ($member->getProfiles() as $profile): ?>
<tr>
<th><?php echo $profile->getCaption() ?></th>
<td><?php echo $member->getProfile($profile->getName()) ?></td>
</tr>
<?php endforeach ?>
<tr>
<th><?php echo __('PCメールアドレス') ?></th>
<td><?php echo $member->getConfig('pc_address') ?></td>
</tr>
<tr>
<th><?php echo __('携帯メールアドレス') ?></th>
<td><?php echo $member->getConfig('mobile_address') ?></td>
</tr>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('強制退会させる') ?>" /></td>
</tr>
</table>
</form>

<?php use_helper('Javascript') ?>
<?php echo link_to_function(__('前のページに戻る'), 'history.back()') ?>

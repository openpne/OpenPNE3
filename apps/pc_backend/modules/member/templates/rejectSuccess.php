<?php slot('submenu'); ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('Ban Setting') ?></h2>

<p><?php echo format_number_choice(
    __('[0]Do you want to ban this member?[1]Do you want to unban this member?'), array(), $member->getIsLoginRejected()
  )
?></p>

<form action="<?php echo url_for('member/reject?id='.$member->getId()) ?>" method="post">
<table>
<tr>
<th><?php echo __('ID') ?></th><td><?php echo $member->getId() ?></td>
</tr>
<tr>
<th><?php echo __('Nickname') ?></th><td><?php echo $member->getName() ?></td>
</tr>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo format_number_choice(
    __('[0]Ban|[1]Unban'), array(), $member->getIsLoginRejected()
  )
?>" /></td>
</tr>
</table>
</form>

<?php use_helper('Javascript') ?>
<?php echo link_to_function(__('Return to previous page'), 'history.back()') ?>

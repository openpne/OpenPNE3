<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<h2><?php echo __('Add a banner image') ?></h2>

<form action="" method="post" enctype="multipart/form-data">
<table><tbody>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('Add') ?>" /></td>
</tr>
</tbody></table>
</form>

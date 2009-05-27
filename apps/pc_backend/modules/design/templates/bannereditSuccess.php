<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<?php $imgParam = array('size' => '180x180', 'alt' => '') ?>

<h2><?php echo __('Modify a banner image') ?></h2>

<form action="" method="post" enctype="multipart/form-data">
<table><tbody>
<tr>
<td style="text-align: center" colspan="2">
<?php
echo link_to(
  image_tag_sf_image($form->getObject()->getFile(), $imgParam),
  sf_image_path($form->getObject()->getFile())
)
?></td>
</tr>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('Modify') ?>" /></td>
</tr>
</tbody></table>
</form>

<?php if (has_component_slot($id . 'Before')) : ?>
<?php include_component_slot($id . 'Before'); ?>
<?php endif; ?>

<div id="<?php echo $id ?>">

<?php if (has_component_slot($id . 'Top')) : ?>
<?php include_component_slot($id . 'Top'); ?>
<?php endif; ?>

<form action="<?php echo $link_to ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="ログイン" /></td>
</tr>
</table>
</form>

<?php if (has_component_slot($id . 'Bottom')) : ?>
<?php include_component_slot($id . 'Bottom'); ?>
<?php endif; ?>

</div>

<?php if (has_component_slot($id . 'After')) : ?>
<?php include_component_slot($id . 'After'); ?>
<?php endif; ?>

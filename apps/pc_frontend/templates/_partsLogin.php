<?php set_entry_point($id, 'Before') ?>

<div id="<?php echo $id ?>">

<?php set_entry_point($id, 'Top') ?>

<form action="<?php echo $link_to ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="ログイン" /></td>
</tr>
</table>
</form>

<?php set_entry_point($id, 'Bottom') ?>

</div>

<?php set_entry_point($id, 'After') ?>

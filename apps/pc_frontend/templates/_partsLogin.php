<?php include_customizes($id, 'before') ?>

<div id="<?php echo $id ?>">

<?php include_customizes($id, 'top') ?>

<form action="<?php echo $link_to ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="ログイン" /></td>
</tr>
</table>
</form>

<?php include_customizes($id, 'bottom') ?>

</div>

<?php include_customizes($id, 'after') ?>

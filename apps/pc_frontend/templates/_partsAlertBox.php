<?php include_customizes($id, 'before') ?>
<div id="<?php echo $id ?>" class="dparts alertBox"><div class="parts">
<table><tr>
<th><img src="<?php echo public_path('images/icon_alert.gif') ?>" alt="" /></th>
<td>
<?php include_customizes($id, 'top') ?>
<?php echo $sf_data->getRaw('body') ?>
<?php include_customizes($id, 'bottom') ?>
</td>
</tr></table>
</div></div>
<?php include_customizes($id, 'after') ?>

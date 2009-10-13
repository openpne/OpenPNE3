<div class="dparts form"><div class="parts">
<div class="partsHeading"><h3><?php echo __('Introduce to your friends this member') ?></h3></div>
<div class="partsInfo">
<p>
<?php echo __('Please choose the friend who wants to introduce this man from a list, and write the message to introduce.') ?>
</p>
</div>

<form method="post" action="">
<table><tbody>

<tr>
<th><?php echo __('Photo') ?></th>
<td><?php echo image_tag_sf_image($member->getImageFilename(), array('size' => '76x76')) ?></td>
</tr>

<tr>
<th><?php echo __('Nickname') ?></th>
<td><?php echo $member->getName() ?></td>
</tr>

<tr>
<th><?php echo $form['introduce_to']->renderLabel() ?></th>
<td>
<?php echo $form->renderHiddenFields() ?>
<div class="checkList">
<?php echo $form['introduce_to']->renderError() ?>
<?php echo $form['introduce_to'] ?>
</div>
</td>
</tr>

<?php echo $form['message']->renderRow() ?>

</tbody></table>

<div class="operation">
<ul class="moreInfo button">
<li><input type="submit" value="<?php echo __('Send') ?>" class="input_submit"/></li>
</ul>
</div>
</form>
</div></div>

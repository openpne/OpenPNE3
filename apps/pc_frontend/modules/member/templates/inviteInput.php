<?php

$options = array(
  'title' => __('Invite a friend to %1%', array('%1%' => $op_config['sns_name'])),
  'url' => url_for('@member_invite'),
  'button' => __('Send'),
);
op_include_form('inviteForm', $form, $options);
?>

<?php if (count($invites)): ?>
<div class="dparts recentList" id="invitelistForm">
<div class="parts">

<div class="partsHeading">
<h3><?php echo __('Mail address list during invitation') ?></h3>
</div>

<?php echo $listform->renderFormTag(url_for('@member_invite')) ?>
<?php $i = 0 ?>
<?php foreach ($listform as $field): ?>
<?php if ($field->isHidden()) continue; ?>
<dl>
<dt><?php echo date(__('Y/m/d'), strtotime($invites[$i]->getCreatedAt())) ?></dt>
<dd>
<?php echo $field ?>
<?php echo $field->renderLabel() ?>
</dd>
</dl>
<?php $i++ ?>
<?php endforeach ?>

<div class="operation">
<ul class="moreInfo button">
<li>
<?php echo $listform->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('Delete') ?>" class="input_submit"/>
</li>
</ul>
</div>
</form>

</div>
</div>
<?php endif ?>

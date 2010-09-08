<?php echo op_include_box('showYourOpenID', url_for('OpenID/member?id='.$sf_user->getMemberId(), true), array('title' => __('Your OpenID'))); ?>

<?php if ($pager->getNbResults()) : ?>
<?php slot('_manage_list'); ?>
<?php op_include_pager_navigation($pager, 'OpenID/list?page=%d'); ?>

<div class="item"><table>
<thead><tr>
<th><?php echo __('External Service') ?></th>
<th><?php echo __('Last Login') ?></th>
<th><?php echo __('Policy of Permission') ?></th>
</tr></thead>
<tbody>
<?php foreach ($pager->getResults() as $item): ?>
<tr>
<td><?php echo $item->uri ?></td>
<td><?php echo op_format_date($item->updated_at, 'XDateTimeJa') ?></td>
<td>
<?php if ($item->is_permanent): ?>
<?php echo __('Always Permit') ?><br />
(<?php echo link_to(__('Unset Permission'), 'OpenID/unsetPermission?id='.$item->id) ?>)
<?php else: ?>
<?php echo __('Ask Each Time') ?>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody></table></div>

<?php op_include_pager_navigation($pager, 'OpenID/list?page=%d'); ?>
<?php end_slot(); ?>

<?php
$params = array(
  'id' => 'openIdManageList',
  'name' => 'openIdManageList',
  'op_content' => get_slot('_manage_list'),
  'options' => array(
    'title' => __('List of Service that You Use'),
  ),
);

include_partial('global/partsLayout', $params);
?>
<?php endif; ?>

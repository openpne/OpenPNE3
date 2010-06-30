<table>
<tr><th>ID</th><td><?php echo $community->getId() ?></td></tr>
<tr><th><?php echo __('%community% Name', array('%community%' => $op_term['community'])) ?></th><td><?php echo $community->getName() ?></td></tr>
<tr><th><?php echo __('%community% Category', array('%community%' => $op_term['community'])) ?></th><td><?php echo $community->getCommunityCategory() ?></td></tr>
<tr><th><?php echo __('%community% Administrator', array('%community%' => $op_term['community'])) ?></th><td><?php echo $community->getAdminMember()->getName() ?></td></tr>
<?php
$subAdminCaption = '';
foreach ($community->getSubAdminMembers() as $m)
{
  $subAdminCaption .= "<li>".$m->getName()."</li>\n";
}?>
<?php if ($subAdminCaption): ?>
<tr><th><?php echo __('Sub Administrator') ?></th><td><ul><?php echo $subAdminCaption ?></ul></td></tr>
<?php endif; ?>
<tr><th><?php echo __('%community% Members', array('%community%' => $op_term['community'])) ?></th><td><?php echo $community->countCommunityMembers() ?></td></tr>
<tr><th><?php echo __('Date Created') ?></th><td><?php echo $community->getCreatedAt() ?></td></tr>
<?php foreach ($community->getConfigs() as $name => $config): ?>
<tr><th><?php echo __(op_replace_sns_term($name), array(), 'form_community') ?></th><td><?php echo nl2br($config) ?></td></tr>
<?php endforeach; ?>
<?php if ($moreInfo): ?>
<tr><td colspan="2">
<ul>
<?php foreach ($sf_data->getRaw('moreInfo') as $more): ?>
<li><?php echo $more ?></li>
<?php endforeach; ?>
</ul>
</td></tr>
<?php endif; ?>
</table>


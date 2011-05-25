<?php if ($accessBlockPager->getNbResults()): ?>
<?php $partsInfo = '<center>'.pager_total($accessBlockPager).'<br></center><hr color="'.$op_color["core_color_12"] .'">'; ?>

<?php
foreach ($accessBlockPager->getResults() as $accessBlock)
{
  $list[] = get_partial('member/accessBlock', array('accessBlock' => $accessBlock));
}

op_include_list('accessBlockList', $list, array('border' => true, 'title' => __('List of access block'), 'partsInfo' => $partsInfo));
?>
<?php if ($accessBlockPager->haveToPaginate()): ?>
<?php op_include_pager_navigation($accessBlockPager, '@member_config?category=accessBlock&page=%d') ?>
<?php endif; ?>
<?php endif; ?>

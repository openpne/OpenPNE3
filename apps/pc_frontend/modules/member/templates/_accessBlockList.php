<?php if ($accessBlockPager->getNbResults()): ?>

<div class="dparts searchResultList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('List of access block') ?> </h3></div>

<?php op_include_pager_navigation($accessBlockPager, '@member_config?category=accessBlock&page=%d') ?>

<div class="block">

<?php
foreach ($accessBlockPager->getResults() as $accessBlock)
{
  echo get_partial('member/accessBlock', array('accessBlock' => $accessBlock));
}

?>
<?php op_include_pager_navigation($accessBlockPager, '@member_config?category=accessBlock&page=%d') ?>
</div></div></div>
<?php endif; ?>

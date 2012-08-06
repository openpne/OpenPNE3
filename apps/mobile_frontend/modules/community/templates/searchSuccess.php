<?php op_mobile_page_title(__('Search %Community%', array('%Community%' => $op_term['community']->pluralize()))) ?>

<?php if ($isResult): ?>
<?php if ($pager->getNbResults()): ?>
<center>
<?php op_include_pager_total($pager); ?>
</center>
<?php
$list = array();
foreach ($pager->getResults() as $community)
{
  $list[] = link_to(sprintf('%s(%d)', $community->getName(), $community->countCommunityMembers()), '@community_home?id='.$community->getId());
}
$option = array(
  'border' => true,
);
op_include_list('communityList', $list, $option);
?>
<?php op_include_pager_navigation($pager, 'community/search?page=%d', array('is_total' => false, 'use_current_query_string' => true)) ?>
<?php else: ?>
<?php echo __('Your search queries did not match any %community%.', array('%community%' => $op_term['community']->pluralize())) ?>
<?php endif ?>
<?php endif ?>

<?php
$options = array(
  'url'    => url_for('community/search'),
  'button' => __('Search'),
  'method' => 'get',
  'align'  => 'center'
);
if (!$isResult)
{
  $options['moreInfo'] = array(link_to(__('Create a new %community%'), '@community_edit'));
}

op_include_form('searchCommunity', $filters, $options);
?>

<?php if ($isResult): ?>

<?php else: ?>
<?php
$list = array();
foreach ($categorys as $category)
{
  $list[] = link_to(sprintf('%s', $category->getName()), 'community/search', array('query_string' => 'community[community_category_id]='.$category->getId()));
}
$option = array(
  'border' => true,
  'title' => __('Categories')
);
op_include_list('searchCategory', $list, $option);
?>
<?php endif ?>

<?php if ($isResult): ?>
<?php slot('op_mobile_footer_menu') ?>
<?php echo link_to(__('Back'), 'community/search'); ?>
<?php end_slot(); ?>
<?php endif ?>

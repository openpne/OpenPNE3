<?php slot('op_sidemenu'); ?>
<?php
$list = array();
foreach ($categories as $key => $value)
{
  if (count($value))
  {
    $list[$key] = link_to(__($categoryCaptions[$key]), 'member/config?category='.$key);
  }
}
op_include_parts('pageNav', 'pageNav', array('list' => $list, 'current' => $categoryName));
?>

<?php
$list = array(
  link_to(__('Connecting with External Application'), 'connection/list'),
  link_to(__('OpenID Configuration'), 'OpenID/list'),
);
op_include_parts('pageNav', 'connection', array('list' => $list));
?>

<?php
$list = array(link_to(__('Delete your %1% account', array('%1%' => $op_config['sns_name'])), 'member/delete'));
op_include_parts('pageNav', 'navForDelete', array('list' => $list));
?>
<?php end_slot(); ?>

<?php if ($categoryName): ?>
<?php op_include_form($categoryName.'Form', $form, array('title' => __($categoryCaptions[$categoryName]), 'url' => url_for('member/config?category='.$categoryName))) ?>
<?php else: ?>
<?php op_include_box('configInformation', __('Please select the item that wants to be set from the menu.'), array('title' => __('Change Settings'))); ?>
<?php endif; ?>

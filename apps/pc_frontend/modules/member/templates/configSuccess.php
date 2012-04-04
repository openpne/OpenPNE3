<?php slot('op_sidemenu'); ?>
<?php
$list = array();
foreach ($categories as $key => $value)
{
  if (count($value))
  {
    $list[$key] = link_to(__($categoryCaptions[$key]), '@member_config?category='.$key);
  }
}
op_include_parts('pageNav', 'pageNav', array('list' => $list, 'current' => $categoryName));
?>

<?php
$list = array();

if (opConfig::get('enable_connection'))
{
  $list[] = link_to(__('Connecting with External Application'), '@connection_list');
}

if (opConfig::get('enable_jsonapi'))
{
  $list[] = link_to(__('JSON API Configuration'), '@member_config_jsonapi');
}

if (opConfig::get('enable_openid'))
{
  $list[] = link_to(__('OpenID Configuration'), '@openid_list');
}

if ($list)
{
  op_include_parts('pageNav', 'connection', array('list' => $list));
}
?>

<?php
$list = array(link_to(__('Delete your %1% account', array('%1%' => $op_config['sns_name'])), '@member_delete'));
op_include_parts('pageNav', 'navForDelete', array('list' => $list));
?>
<?php end_slot(); ?>

<?php if ($categoryName && $form->count() > 1): // except CSRF token field ?>
<?php op_include_form($categoryName.'Form', $form, array('title' => __($categoryCaptions[$categoryName]), 'url' => url_for('@member_config?category='.$categoryName))) ?>
<?php elseif ($categoryName && 1 === $form->count()) : ?>
<?php op_include_box('configInformation', __('There is no available settings.'), array('title' => __($categoryCaptions[$categoryName]))); ?>
<?php else: ?>
<?php op_include_box('configInformation', __('Please select the item that wants to be set from the menu.'), array('title' => __('Change Settings'))); ?>
<?php endif; ?>

<?php slot('op_sidemenu'); ?>
<?php
$list = array();
foreach ($categories as $key => $value)
{
  if (count($value))
  {
    $list[$key] = link_to($categoryCaptions[$key], 'member/config?category='.$key);
  }
}
op_include_parts('pageNav', 'pageNav', array('list' => $list, 'current' => $categoryName));
?>

<?php
op_include_box('navForDelete', link_to(__('%1%を退会する', array('%1%' => $op_config['sns_name'])), 'member/delete'));
?>
<?php end_slot(); ?>

<?php if ($categoryName): ?>
<?php op_include_form($categoryName.'Form', $form, array('title' => $categoryCaptions[$categoryName], 'url' => 'member/config?category='.$categoryName)) ?>
<?php else: ?>
<?php op_include_box('configInformation', __('メニューから設定したい項目を選択してください。'), array('title' => __('設定変更'))); ?>
<?php endif; ?>

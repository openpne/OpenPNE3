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
include_parts('pageNav', 'pagenavi', array('list' => $list, 'current' => $categoryName));
?>
<?php end_slot(); ?>

<?php if ($categoryName) : ?>
<?php include_box('form'.$categoryName, $categoryCaptions[$categoryName], '', array(
  'form' => array($form),
  'url' => 'member/config?category='.$categoryName)
) ?>
<?php else: ?>
<?php include_box('configInformation', __('設定変更'), __('メニューから設定したい項目を選択してください。')); ?>
<?php endif; ?>

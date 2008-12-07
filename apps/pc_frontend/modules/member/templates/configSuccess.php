<?php slot('op_sidemenu'); ?>
<?php
$list = array();
foreach ($categories as $key => $value)
{
  $list[$key] = link_to($key, 'member/config?category='.$key);
}
include_parts('pageNav', 'pagenavi', array('list' => $list, 'current' => $categoryName));
?>
<?php end_slot(); ?>

<?php if (count($form)) : ?>
<?php include_box('form'.$categoryName, $categoryName, '', array(
  'form' => array($form),
  'url' => 'member/config?category='.$categoryName)
) ?>
<?php endif; ?>

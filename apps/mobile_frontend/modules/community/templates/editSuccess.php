<?php 
$subtitle = null;
$url = '@community_edit';
if($communityForm->isNew())
{
  $title = __('Create %community%');
}
else
{
  $title = __('Edit %community%');
  $subtitle = $community->getName();
  $url .= '?id='.$community->getId();
}
?>

<?php op_mobile_page_title($title, $subtitle) ?>

<?php op_include_form('communityForm', array($communityForm, $communityConfigForm), array(
  'url' => url_for($url),
  'button' => __('Save'),
  'align' => 'center',
)) ?>

<?php if (!$communityForm->isNew()): ?>
<?php
  op_include_parts('buttonBox', 'deleteForm', array(
    'title' => __('Delete this %community%'),
    'body' => __('delete this %community%.if you delete this %community% please to report in advance for all this %community% members.'),
    'button' => __('Delete'),
    'method' => 'get',
    'url' => url_for('@community_delete?id=' . $community->getId()),
  ));
?>
<hr color="<?php echo $op_color['core_color_11'] ?>">
<?php echo link_to(__('%Community% Top'), '@community_home?id='.$community->getId()) ?>
<?php endif; ?>

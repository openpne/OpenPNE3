<?php op_mobile_page_title($community->getName(), __('Detail of %community%')) ?>

<table width="100%" bgcolor="<?php echo $op_color["core_color_4"] ?>">
<tr><td colspan="2" align="center">
<?php include_customizes('menu', 'top') ?>
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
</td></tr>

<?php
$list = array(
  __('%community% Name')     => $community->getName(),
  __('%community% Category', array(), 'form_community') => $community->getCommunityCategory(),
  __('Date Created')       => op_format_date($community->getCreatedAt(), 'D'),
  __('Administrator')      => op_link_to_member($communityAdmin),
);
$subAdminCaption = array();
foreach ($communitySubAdmins as $m)
{
  $subAdminCaption[] = op_link_to_member($m);
}
if (count($subAdminCaption))
{
  $list[__('Sub Administrator')] = implode("<br>\n", $subAdminCaption);
}
$list[__('Count of Members')] = $community->countCommunityMembers();
foreach ($community->getConfigs() as $key => $config)
{
  $list[__($key, array(), 'form_community')] = $config;
}
$list[__('Register policy', array(), 'form_community')] = __($sf_data->getRaw('community')->getRegisterPolicy());
$list[__('%community% Description', array(), 'form_community')] = op_auto_link_text_for_mobile(nl2br($community->getConfig('description')));
?>
<?php foreach ($list as $key => $value): ?>
<font color="<?php echo $op_color["core_color_19"] ?>"><?php echo $key ?>:</font><br>
<?php echo $value ?><br>
<?php endforeach; ?>

<hr color="<?php echo $op_color['core_color_11'] ?>">

<?php echo link_to(__('%Community% Top'), '@community_home?id='.$community->getId()) ?>

<?php slot('op_sidemenu'); ?>
<?php
$options = array(
  'object' => $community,
);
op_include_parts('memberImageBox', 'communityImageBox', $options);
?>

<?php
$options = array(
  'title' => __('%community% Members', array('%community%' => $op_term['community']->titleize())),
  'list' => $members,
  'crownIds' => array($communityAdmin->getId()),
  'link_to' => '@member_profile?id=',
  'moreInfo' => array(link_to(sprintf('%s(%d)', __('Show all'), $community->countCommunityMembers()), 'community/memberList?id='.$community->getId())),
);
if ($isAdmin || $isSubAdmin)
{
  $options['moreInfo'][] = link_to(__('Management member'), 'community/memberManage?id='.$community->getId());
}
op_include_parts('nineTable', 'frendList', $options);
?>
<?php end_slot(); ?>

<?php slot('op_top') ?>
<?php if ($isCommunityPreMember) : ?>
<?php op_include_parts('descriptionBox', 'informationAboutCommunity',  array('body' => __('You are waiting for the participation approval by %community%\'s administrator.'))) ?>
<?php endif; ?>
<?php end_slot(); ?>

<?php
$list = array(__('%community% Name', array('%community%' => $op_term['community']->titleize())) => $community->getName());
if ($community->community_category_id)
{
  $list[__('%community% Category', array('%community%' => $op_term['community']->titleize()))] = $community->getCommunityCategory();
}
$list += array(__('Date Created')       => op_format_date($community->getCreatedAt(), 'D'),
               __('Administrator')      => link_to($communityAdmin->getName(), '@member_profile?id='.$communityAdmin->getId()),
);
$subAdminCaption = '';
foreach ($communitySubAdmins as $m)
{
  $subAdminCaption .= "<li>".link_to($m->getName(), '@member_profile?id='.$m->getId())."</li>\n";
}
if ($subAdminCaption)
{
  $list[__('Sub Administrator')] = '<ul>'.$subAdminCaption.'</ul>';
}
$list[__('Count of Members')] = $community->countCommunityMembers();
foreach ($community->getConfigs() as $key => $config)
{
  if ('%community% Description' === $key)
  {
    $list[__('%community% Description', array('%community%' => $op_term['community']->titleize()), 'form_community')] = op_url_cmd(nl2br($community->getConfig('description')));
  }
  else
  {
    $list[__($key, array(), 'form_community')] = $config;
  }
}
$list[__('Register policy', array(), 'form_community')] = __($community->getRawValue()->getRegisterPolicy());

$options = array(
  'title' => __('%community%', array('%community%' => $op_term['community']->titleize())),
  'list' => $list,
);
op_include_parts('listBox', 'communityHome', $options);
?>

<ul>
<?php if ($isEditCommunity): ?>
<li><?php echo link_to(__('Edit this %community%'), 'community/edit?id=' . $community->getId()) ?></li>
<?php endif; ?>

<?php if (!$isAdmin): ?>
<?php if ($isCommunityMember): ?>
<li><?php echo link_to(__('Leave this %community%'), 'community/quit?id=' . $community->getId()) ?></li>
<?php else : ?>
<li><?php echo link_to(__('Join this %community%'), 'community/join?id=' . $community->getId()) ?></li>
<?php endif; ?>
<?php endif; ?>
</ul>

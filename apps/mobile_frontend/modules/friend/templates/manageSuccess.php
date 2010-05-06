<?php op_mobile_page_title(__('Manage %friend%')) ?>

<center>
<?php op_include_pager_total($pager); ?>
</center>

<?php
$list = array();
foreach ($pager->getResults() as $member)
{
  $vars = array('id' => $member->getId());
  $list[] = get_customizes('id_name', 'before', $vars)
          . op_link_to_member($member, array('link_target' => sprintf('%s(%d)', $member->getName(), $member->countFriends()))).'<br>'
          . get_customizes('id_name', 'after', $vars)
          . get_customizes('id_friend', 'before', $vars)
          . ' ['.link_to(__('Removes this %friend%'), 'friend/unlink?id='.$member->getId()).']'
          . get_customizes('id_friend', 'after', $vars);
}

$option = array(
  'border' => true,
);

op_include_list('friendList', $list, $option);
?>

<?php op_include_pager_navigation($pager, 'friend/manage?page=%d&id=' . $sf_params->get('id'), array('is_total' => false)); ?>

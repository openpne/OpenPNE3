<?php
$options = array(
  'title'  => __('Search Members'),
  'url'    => url_for('@member_search'),
  'button' => __('Search'),
  'method' => 'get'
);

op_include_form('searchMember', $filters, $options);
?>

<?php if ($pager->getNbResults()): ?>
<?php
$list = array();
foreach ($pager->getResults() as $key => $member)
{
  $list[$key] = array();
  $list[$key][__('%nickname%', array('%nickname%' => $op_term['nickname']->titleize()))] = $member->getName();
  if ($member->getProfile('op_preset_self_introduction'))
  {
    $list[$key][__('Self Introduction')] = $member->getProfile('op_preset_self_introduction');
  }
  $list[$key][__('Last Login')] = op_format_last_login_time($member->getLastLoginTime());
}

$options = array(
  'title'          =>  __('Search Results'),
  'pager'          => $pager,
  'link_to_page'   => '@member_search?page=%d',
  'link_to_detail' => '@member_profile?id=%d',
  'list'           => $list,
  'use_op_link_to_member' => true,
);

op_include_parts('searchResultList', 'searchCommunityResult', $options);
?>
<?php else: ?>
<?php op_include_box('searchMemberResult', __('Your search queries did not match any members.'), array('title' => __('Search Results'))) ?>
<?php endif; ?>

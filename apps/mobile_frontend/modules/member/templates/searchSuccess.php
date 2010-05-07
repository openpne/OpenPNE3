<?php op_mobile_page_title(__('Search Members')) ?>

<?php if ($pager->getNbResults()): ?>
<center>
<?php op_include_pager_total($pager); ?>
</center>
<?php
$list = array();
foreach ($pager->getResults() as $member)
{
  $list[] = op_link_to_member($member);
}
$option = array(
  'border' => true,
);
op_include_list('memberList', $list, $option);
?>
<?php op_include_pager_navigation($pager, '@member_search?page=%d', array('is_total' => false, 'use_current_query_string' => true)) ?>
<?php else: ?>
<?php echo __('Your search queries did not match any members.') ?>
<?php endif ?>

<?php
$options = array(
  'url'    => url_for('@member_search'),
  'button' => __('Search'),
  'method' => 'get',
  'align'  => 'center'
);
?>

<table width="100%">
<tbody><tr><td bgcolor="<?php echo $op_color["core_color_5"] ?>">
<font color="<?php $op_color["core_color_25"] ?>">
<?php echo __('Search Members') ?>
</font><br/>
</td></tr>
</tbody></table>

<?php op_include_form('searchMember', $filters, $options); ?>

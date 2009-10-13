<?php
slot('op_sidemenu');
include_parts('rankingLink', 'RankingLink');
end_slot();
include_box('access_list', __('Access number No1 member'), __('all member is 0 access'));
?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>

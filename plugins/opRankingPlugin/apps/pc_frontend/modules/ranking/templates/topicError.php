<?php
slot('op_sidemenu');
include_parts('rankingLink', 'RankingLink');
end_slot();
include_box('community_list', __('No1 community at each upsurge'), __('Not written'));
?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>

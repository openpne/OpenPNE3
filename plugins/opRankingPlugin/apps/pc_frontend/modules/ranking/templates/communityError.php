<?php
slot('op_sidemenu');
include_parts('rankingLink', 'RankingLink');
end_slot();
include_box('community_list', __('Participation number No1 community'), __('No community'));
?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>

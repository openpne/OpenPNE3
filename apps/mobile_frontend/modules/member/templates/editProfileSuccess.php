<?php op_mobile_page_title(__('Edit profile')) ?>

<?php op_include_form('profileForm', array($memberForm, $profileForm), array(
    'url'    => url_for('@member_editProfile'),
    'align'  => 'center',
    'button' => __('Save')
)) ?>

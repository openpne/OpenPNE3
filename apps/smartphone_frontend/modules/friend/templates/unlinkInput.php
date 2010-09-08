<?php
op_include_parts('yesNo', 'unlinkConfirmForm', array(
  'title' => __('Do you delete %0% from %my_friend%?', array('%0%' => link_to($member->getName(), '@member_profile?id='.$member->getId()))),
  'yes_form' => new sfForm(),
  'no_method' => 'get',
  'no_url' => url_for('@friend_manage')
)) ?>

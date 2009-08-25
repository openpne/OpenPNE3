<?php op_include_parts('yesNo', 'dropMemberConfirmForm', array(
  'body'      => __('Do you drop %0% from this community?', array('%0%' => $member->getName())),
  'no_url'    => url_for('community/memberManage?id='.$community->getId()),
  'no_method' => 'get',
)) ?>

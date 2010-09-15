<?php op_include_parts('yesNo', 'dropMemberConfirmForm', array(
  'body'      => __('Do you drop %0% from this %community%?', array('%0%' => $member->getName())),
  'yes_form'  => new sfForm(),
  'no_url'    => url_for('@community_memberManage'),
  'no_method' => 'get',
  'no_form'   => '<input type="hidden" name="id" value="'.$community->getId().'"/>',
)) ?>

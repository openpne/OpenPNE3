<?php op_include_parts('yesNo', 'removeSubAdminConfirmForm', array(
  'body'      => __("Do you demotion %0% from this %community%'s sub-administrator?", array('%0%' => $member->getName())),
  'yes_form'  => new BaseForm(),
  'no_url'    => url_for('@community_memberManage?id='.$community->getId()),
  'no_method' => 'get',
)) ?>

<?php
$options = array(
  'title'   => __('Remove the App').": ".$application->getTitle(),
  'body'    => __('Do you really delete this App?'),
  'no_url'  => url_for('@my_application_list'),
  'no_method' => 'get',
);

op_include_yesno('ApplicationRemoveConfirm', new sfForm(), new sfForm(array(),array(),false), $options);


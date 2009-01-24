<?php
$options = array(
  'name'     => $sf_user->getMember()->getName(),
  'image'    => $sf_user->getMember()->getImageFileName(),
  'moreInfo' => array(
    link_to(__('Edit Photo'), 'member/configImage'),
    link_to(__('Show Profile'), 'member/profile'),
  ),
);
op_include_parts('memberImageBox', 'memberImageBox', $options);

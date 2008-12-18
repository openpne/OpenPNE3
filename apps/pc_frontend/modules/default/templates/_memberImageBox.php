<?php include_parts('memberImageBox', 'image', array(
  'name'     => $sf_user->getMember()->getName(),
  'image'    => $sf_user->getMember()->getImageFileName(),
  'moreInfo' => array(link_to('写真を編集', 'member/configImage')),
)) ?>

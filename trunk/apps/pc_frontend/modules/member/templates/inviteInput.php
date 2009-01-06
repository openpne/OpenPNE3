<?php include_box('formInvite', '友人を'.OpenPNEConfig::get('sns_name').'に招待する', '', array(
  'form' => array($form),
  'url' => 'member/invite',
  'button' => '送信',
)) ?>

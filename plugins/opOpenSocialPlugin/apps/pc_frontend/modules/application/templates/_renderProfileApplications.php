<?php
use_helper('OpenSocial');
op_include_application_setting();
$maxNum = (int)$gadget->getConfig('num');
$i = 0;
?>

<?php foreach ($memberApplications as $memberApplication): ?>
<?php if ($i >= $maxNum) break; ?>
<?php if ($memberApplication->getApplicationSetting('is_view_profile')): ?>
<?php include_component('application', 'gadget', array(
  'view' => 'profile',
  'memberApplication' => $memberApplication,
  'titleLinkTo' => '@application_canvas?id='.$memberApplication->getId(),
)) ?>
<?php $i++ ?>
<?php endif; ?>
<?php endforeach; ?>

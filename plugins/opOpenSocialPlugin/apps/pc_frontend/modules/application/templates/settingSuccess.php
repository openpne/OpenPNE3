<?php if(isset($isValid) && $isValid): ?>
<?php use_helper('Javascript') ?>
<?php echo javascript_tag("
var modal = parent.$('opensocial_modal_box');
var modalContents = parent.$('opensocial_modal_box_contents');
var modalIframe = modalContents.getElementsByTagName('iframe')[0];
Element.hide(modal);
Element.hide(modalContents);
"); ?>
<?php if($sf_params->get('is_reload')) : ?>
<?php echo javascript_tag("
parent.location.href = parent.location.pathname + parent.location.search;
") ?>
<?php endif ?>
<?php endif ?>

<?php op_include_form('formAppSetting', array($settingForm, $userSettingForm), array(
  'title' => __('App Settings: %0%', array('%0%' => $memberApplication->getApplication()->getTitle())), 
  'url'   => url_for('@application_setting?id='.$memberApplication->getId().'&is_reload='.$sf_params->get('is_reload', false))
)) ?>

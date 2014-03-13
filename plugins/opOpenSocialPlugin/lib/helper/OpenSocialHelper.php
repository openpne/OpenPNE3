<?php

/**
 * OpenSocialHelper
 *
 * @package    OpenPNE
 * @subpackage helper
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */

sfProjectConfiguration::getActive()->loadHelpers(array('Url', 'Javascript', 'opJavascript'));

/**
 * include application information box
 *
 * @param integer     $id
 * @param Application $application a instance of the Application
 * @param integer     $mid         a module id
 * @param boolean     $isOwner 
 */
function op_include_application_information_box($id, $application, $mid = null, $isOwner = false)
{
  $params = array(
    'id'          => $id,
    'application' => $application,
    'mid'         => $mid,
    'isOwner'     => $isOwner
  );
  include_partial('application/informationBox', $params);
}

function op_include_application_setting()
{
  static $isFirst = true;
  if ($isFirst)
  {
    $opOpenSocialContainerConfig = new opOpenSocialContainerConfig();
    $opOpenSocialContainerConfig->generateAndSave();

    $response = sfContext::getInstance()->getResponse();
    $response->addJavascript('/sfProtoculousPlugin/js/prototype');
    $response->addJavascript('/opOpenSocialPlugin/js/tabs-min');
    $response->addJavascript('/opOpenSocialPlugin/js/container');
    $response->addJavascript('/gadgets/js/rpc.js?c=1');

    $request = sfContext::getInstance()->getRequest();
    $isDev   = sfConfig::get('sf_environment') == 'dev';

    $snsUrl  = $request->getUriPrefix().$request->getRelativeUrlRoot();
    $snsUrl .= $isDev ? '/pc_frontend_dev.php' : '';

    $apiUrl  = $request->getUriPrefix().$request->getRelativeUrlRoot().'/api';
    $apiUrl .= $isDev ? '_dev' : '';
    $apiUrl .= '.php'; 
    
    echo javascript_tag(sprintf(<<<EOF
gadgets.container = new Container("%s", "%s");
EOF
  ,$snsUrl, $apiUrl
));
    echo make_app_setting_modal_box('opensocial_modal_box');
    $isFirst = false;
  }
}

function link_to_app_setting($text, $mid, $isReload = false)
{
  $response = sfContext::getInstance()->getResponse();
  $response->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/prototype');
  $response->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/builder');
  $response->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/effects');
  $response->addJavascript('/opOpenSocialPlugin/js/opensocial-util');
  $url = '@application_setting?id='.$mid;
  if ($isReload)
  {
    $url = $url.'&is_reload=1';
  }
  return link_to_function($text,"showIframeModalBox('opensocial_modal_box','".url_for($url)."')");
}

function make_app_setting_modal_box($id)
{
  return make_modal_box($id, '<iframe width="400" height="400" frameborder="0"></iframe>');
}

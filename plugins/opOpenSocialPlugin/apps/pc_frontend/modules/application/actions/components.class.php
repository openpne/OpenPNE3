<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * application components
 *
 * @package    OpenPNE
 * @subpackage opOpenSocialPlugin
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class applicationComponents extends sfComponents
{
 /**
  * Executes gadget component
  *
  * @param sfWebRequest $request
  */
  public function executeGadget(sfWebRequest $request)
  {
    $this->isTitleLink = true;
    if (!$this->titleLinkTo)
    {
      $this->isTitleLink = false;
      $this->titleLinkTo = '@homepage';
    }

    $culture = $this->getUser()->getCulture();
    $culture = explode("_", $culture);
    $this->application = $this->memberApplication->getApplication();
    $this->height      = $this->application->getHeight() ? $this->application->getHeight() : 200;

    $viewerId = $this->getUser()->getMemberId();

    $this->isOwner = false;
    if ($this->memberApplication->getMemberId() == $viewerId)
    {
      $this->isOwner = true;
    }

    $isUseOuterShindig = Doctrine::getTable('SnsConfig')->get('is_use_outer_shindig', false);

    $opOpenSocialContainerConfig = new opOpenSocialContainerConfig();
    $containerName = $opOpenSocialContainerConfig->getContainerName();

    $securityToken = opShindigSecurityToken::createFromValues(
      $this->memberApplication->getMemberId(),  // owner
      $viewerId,                                // viewer
      $this->application->getId(),              // app id
      $containerName,                           // domain key
      urlencode($this->application->getUrl()),  // app url
      $this->memberApplication->getId(),        // mod id
      Shindig_Config::get('container_id')
    );

    $getParams = array(
      'synd'      => $containerName,
      'container' => $containerName,
      'owner'     => $this->memberApplication->getMemberId(),
      'viewer'    => $viewerId,
      'aid'       => $this->application->getId(),
      'mid'       => $this->memberApplication->getId(),
      'country'   => isset($culture[1]) ? $culture[1] : 'ALL',
      'lang'      => $culture[0],
      'view'      => $this->view,
      'parent'    => $this->getRequest()->getUri(),
      'st'        => base64_encode($securityToken->toSerialForm()),
      'url'       => $this->application->getUrl(),
    );

    $userprefParamPrefix = Shindig_Config::get('userpref_param_prefix','up_');
    foreach ($this->memberApplication->getUserSettings() as $name => $value)
    {
      $getParams[$userprefParamPrefix.$name] = $value;
    }
    if ($isUseOuterShindig)
    {
      $shindigUrl = Doctrine::getTable('SnsConfig')->get('shindig_url');
      if (substr($shindigUrl, -1) !== '/')
      {
        $shindigUrl .= '/';
      }
      $this->iframeUrl = $shindigUrl.'gadgets/ifr?'.http_build_query($getParams).'#rpctoken='.rand(0,getrandmax());
    }
    else
    {
      $this->iframeUrl = sfContext::getInstance()->getController()->genUrl('gadgets/ifr').'?'.http_build_query($getParams).'#rpctoken='.rand(0,getrandmax());
    }
  }

 /**
  * Executes render home applications component
  *
  * @param sfWebRequest $request
  */
  public function executeRenderHomeApplications(sfWebRequest $request)
  {
    $this->memberApplications = Doctrine::getTable('MemberApplication')->getMemberApplications($this->getUser()->getMemberId());
  }

 /**
  * Executes render profile application component
  *
  * @param sfWebRequest $request
  */
  public function executeRenderProfileApplications(sfWebRequest $request)
  {
    $ownerId  = $request->getParameter('id', $this->getUser()->getMemberId());
    $viewerId = $this->getUser()->getMemberId();
    $this->memberApplications = Doctrine::getTable('MemberApplication')->getMemberApplications($ownerId, $viewerId);
  }
}

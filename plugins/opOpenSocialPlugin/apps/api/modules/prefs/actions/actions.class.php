<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * prefs actions.
 *
 * @package    opOpenSocialPlugin
 * @subpackage action
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class prefsActions extends sfActions
{
  /**
   * Executes set action
   * 
   * @param sfWebRequest $request A request object
   */
  public function executeSet(sfWebRequest $request)
  {
    $response = $this->getResponse();
    if ($request->isMethod(sfRequest::POST) || !$request->hasParameter('st') || !$request->hasParameter('name') || ! $request->hasParameter('value'))
    {
      $this->forward404();
    }
    try
    {
      $st    = urldecode(base64_decode($request->getParameter('st')));
      $name  = $request->getParameter('name');
      $value = $request->getParameter('value');
      $token = opShindigSecurityToken::createFromToken($st, 60);
      $modId = $token->getModuleId();
      $owner = $token->getOwnerId();
      $viewer = $token->getViewerId();

      $this->forward404Unless($viewer == $owner);

      $memberApplication = Doctrine::getTable('MemberApplication')->find($modId);
      $this->forward404Unless($memberApplication);

      $memberApplication->setUserSetting($name, $value);
    }
    catch (Exception $e)
    {
      $this->forward404();
    }

    return sfView::NONE;
  }
}

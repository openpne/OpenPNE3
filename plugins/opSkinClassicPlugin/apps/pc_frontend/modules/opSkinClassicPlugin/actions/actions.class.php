<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opSkinClassicPlugin actions.
 *
 * @package    OpenPNE
 * @subpackage skin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opSkinClassicPluginActions extends sfActions
{
  public function executeCss(sfWebRequest $request)
  {
    opSkinClassicConfig::setCurrentTheme($request->getParameter('theme'));
  }

  public function executeLogin(sfWebRequest $request)
  {
    if ('@opSkinClassicPlugin_login' !== opConfig::get('external_pc_login_url'))
    {
      $this->redirect('member/login');
    }

    $this->form = $this->getUser()->getAuthForm();
    unset($this->form['next_uri']);
  }
}

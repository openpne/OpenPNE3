<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sns actions.
 *
 * @package    OpenPNE
 * @subpackage sns
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class snsActions extends sfActions
{
 /**
  * Executes config action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfig($request)
  {
    $this->form = new SnsConfigForm();

    if ($request->isMethod('post')) {
      $this->form->bind($request->getParameter('sns_config'));
      if ($this->form->isValid()) {
        $this->form->save();
      }
    }
  }

 /**
  * Executes configInformation action
  *
  * @param sfRequest $request A request object
  */
  public function executeInformationConfig($request)
  {
    $this->target = $request->getParameter('target', 'mobile_home');
    $this->form = new InformationConfigForm(array(), array('target' => $this->target));

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('information'));
      if ($this->form->isValid())
      {
        $this->form->save();
      }
    }
  }
}

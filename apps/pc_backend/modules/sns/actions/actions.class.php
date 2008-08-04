<?php

/**
 * sns actions.
 *
 * @package    OpenPNE
 * @subpackage sns
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
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
}

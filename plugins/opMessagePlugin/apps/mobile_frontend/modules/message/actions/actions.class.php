<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * message actions.
 *
 * @package    OpenPNE
 * @subpackage message
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class messageActions extends opMessagePluginMessageActions
{
 /**
  * get title
  *
  * @param string $type
  */
  protected function getTitle($type = null)
  {

    if (is_null($type))
    {
      $type = sfContext::getInstance()->getRequest()->getParameter('type');
    }
    switch ($type)
    {
      case 'receive' :
        return $this->title = 'Inbox';
      case 'send' :
        return $this->title = 'Sent Messages';
      case 'draft' :
        return $this->title = 'Drafts';
      case 'dust' :
        return $this->title = 'Trash';
    }
  }

 /**
  * Executes list action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeList(sfWebRequest $request)
  {
    $this->title = $this->getTitle();
    parent::executeList($request);
  }

 /**
  * Execute show action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeShow(sfWebRequest $request)
  {
    $this->title = $this->getTitle();
    parent::executeShow($request);
  }
}

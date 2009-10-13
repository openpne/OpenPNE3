<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * favorite actions.
 *
 * @package    OpenPNE
 * @subpackage favorite
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class favoriteActions extends opFavoritePluginFavoriteActions
{
 /**
  * Executes add action
  *
  * @param sfRequest $request A request object
  */
  public function executeAdd($request)
  {
    $this->idCheck();
    FavoritePeer::add($this->getUser()->getMemberId(), $this->id);
    $this->redirect('favorite/list');
  }
}

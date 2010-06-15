<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * urlProxy action.
 *
 * @package    OpenPNE
 * @subpackage default
 * @author     Shogo Kawahara <kawahara@tejimaya.com>
 */
class urlProxyAction extends sfAction
{
  public function execute($request)
  {
    $this->forward404Unless($request->hasParameter('url'));

    try
    {
      $zendUri = Zend_Uri_Http::fromString($request->getParameter('url'));
    }
    catch (Exception $e)
    {
      return sfView::ERROR;
    }

    $this->url = $zendUri->getUri();
    $this->proxys = sfConfig::get('op_mobile_proxys');
  }
}

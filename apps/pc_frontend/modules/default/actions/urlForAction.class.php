<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * default actions.
 *
 * @package    OpenPNE
 * @subpackage default
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
class urlForAction extends sfAction
{
 /**
  * Executes urlFor action
  *
  * @param opWebRequest $request A request object
  */
  public function execute($request)
  {
    // for javascript.
    $this->forward404Unless($request->isXmlHttpRequest());
    $this->getResponse()->setContentType('text/plain');
    $this->getResponse()->setHttpHeader('X-Content-Type-Options', 'nosniff', true);

    $this->getContext()->getConfiguration()->loadHelpers(array('Url', 'opUtil'));

    $application = $request['application'];
    $params = (array)$request['params'];
    $function = 'url_for';

    if ($application)
    {
      $params = array_merge(array($application), $params);
      $function = 'app_url_for';
    }

    try
    {
      return $this->renderText(call_user_func_array($function, $params));
    }
    catch (Exception $e)
    {
      $this->logMessage($e->getMessage(), 'err');
    }

    return $this->set422Status('Can not find routing.');
  }

  protected function set422Status($message)
  {
    $this->getResponse()->setStatusCode(422, 'Unprocessable Entity');

    return $this->renderText($message);
  }
}

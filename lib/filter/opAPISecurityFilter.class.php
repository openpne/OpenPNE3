<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAPISecurityFilter
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAPISecurityFilter extends sfBasicSecurityFilter
{
  public function execute($filterChain)
  {
    // the user is not authenticated
    if (!$this->context->getUser()->isAuthenticated())
    {
      // HTTP 401 Unauthorized
      $this->sendError(401);
    }

    // the user doesn't have access
    $credential = $this->getUserCredential();
    if (!is_null($credential) && !$this->context->getUser()->hasCredential($credential))
    {
      // HTTP 403 Forbidden
      $this->sendError(403);
    }

    // the user has access, continue
    $filterChain->execute();
  }

  protected function sendError($statusCode)
  {
    $response = $this->getContext()->getResponse();
    $response->setStatusCode($statusCode);

    foreach ($response->getHttpHeaders() as $name => $value)
    {
      header($name.': '.$value);
    }

    echo $response->getStatusCode().' '.$response->getStatusText();

    throw new sfStopException();
  }
}

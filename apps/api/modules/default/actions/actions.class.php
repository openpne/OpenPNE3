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
 * @author     Kimura Youichi <kim.upsilon@gmail.com>
 */
class defaultActions extends sfActions
{
  public function executeError400(sfWebRequest $request)
  {
    $this->getResponse()->setStatusCode(400);

    return $this->renderError($request['error_message']);
  }

  public function executeError401(sfWebRequest $request)
  {
    $this->getResponse()->setStatusCode(401);

    return $this->renderError($request['error_message']);
  }

  public function executeError403(sfWebRequest $request)
  {
    $this->getResponse()->setStatusCode(403);

    return $this->renderError($request['error_message']);
  }

  public function executeError404(sfWebRequest $request)
  {
    $this->getResponse()->setStatusCode(404);

    return $this->renderError($request['error_message']);
  }

  protected function renderError($message = null)
  {
    $response = $this->getResponse();
    $response->setContentType('text/plain');

    $errorText = $response->getStatusCode().' '.$response->getStatusText();

    if ($message)
    {
      $errorText .= ': '.$message;
    }

    return $this->renderText($errorText);
  }
}

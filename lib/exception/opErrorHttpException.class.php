<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opErrorHttpException
 *
 * @package    OpenPNE
 * @subpackage exception
 * @author     Kimura Youichi <kim.upsilon@gmail.com>
 */
class opErrorHttpException extends sfError404Exception
{
  protected
    $httpStatusCode = 404;

  public function getHttpStatusCode()
  {
    return $this->httpStatusCode;
  }

  public function setHttpStatusCode($statusCode)
  {
    $this->httpStatusCode = $statusCode;

    return $this;
  }

  /**
   * Forwards to the error action.
   */
  public function printStackTrace()
  {
    $exception = is_null($this->wrappedException) ? $this : $this->wrappedException;

    if (sfConfig::get('sf_debug'))
    {
      $response = sfContext::getInstance()->getResponse();
      if (is_null($response))
      {
        $response = new sfWebResponse(sfContext::getInstance()->getEventDispatcher());
        sfContext::getInstance()->setResponse($response);
      }

      $response->setStatusCode($this->httpStatusCode);

      return sfException::printStackTrace(); // skip sfError404Exception::printStackTrace()
    }
    else
    {
      // log all exceptions in php log
      if (!sfConfig::get('sf_test'))
      {
        error_log($this->getMessage());
      }

      if ($this->getMessage())
      {
        sfContext::getInstance()->getRequest()->setParameter('error_message', $this->getMessage());
      }

      $module = sfConfig::get('sf_error_'.$this->httpStatusCode.'_module', sfConfig::get('sf_error_404_module', 'default'));
      $action = sfConfig::get('sf_error_'.$this->httpStatusCode.'_action', sfConfig::get('sf_error_404_action', 'error'));
      sfContext::getInstance()->getController()->forward($module, $action);
    }
  }
}

<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMobileFrontWebController
 *
 * @package    OpenPNE
 * @subpackage controller
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMobileFrontWebController extends opFrontWebController
{
 /**
  * @see sfWebController
  */
  public function genUrl($parameters = array(), $absolute = false)
  {
    if (!defined('SID') || !SID)
    {
      return parent::genUrl($parameters, $absolute);
    }

    $isSid = false;

    if (is_string($parameters) && false !== ($sidPos = strpos($parameters, SID)))
    {
      $isSid = true;
      $paramHead = substr($parameters, 0, $sidPos);
      $paramFoot = substr($parameters, $sidPos + strlen(SID) + 1);
      $parameters = $paramHead.$paramFoot;
    }
    elseif (is_array($parameters) && in_array(session_name(), $parameters, true))
    {
      $isSid = true;
      unset($parameters[session_name()]);
    }

    $url = parent::genUrl($parameters, $absolute);

    if ($isSid)
    {
      $fragment = '';
      if (false !== ($fragPos = strpos($url, '#')))
      {
        $fragment = substr($url, $fragPos);
        $url = substr($url, 0, $fragPos);
      }

      if (strpos($url, '?') === false)
      {
        $url .= '?';
      }
      $url .= SID.$fragment;
    }

    return $url;
  }

 /**
  * @see sfWebController
  */
  public function redirect($url, $delay = 0, $statusCode = 302)
  {
    $url = $this->genUrl($url, true);

    if (!$this->context->getRequest()->isCookie())
    {
      $matchd = '/'.preg_quote(session_name(),'/').'=.*([&|#]?)/';
      if (preg_match($matchd, $url))
      {
        $url = preg_replace($matchd, SID.'\1', $url);
      }
      else
      {
        if (strpos($url, '?') !== false)
        {
          $url .= '&';
        }
        else
        {
          $url .= '?';
        }

        $url .= SID;
      }
    }

    parent::redirect($url, $delay, $statusCode);
  }
}

<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * opMobileFrontWebController
 *
 * @package    OpenPNE
 * @subpackage controller
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMobileFrontWebController extends sfFrontWebController
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
    elseif (is_array($parameters) && in_array($parameters, session_name()))
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

      $url .= '?'.SID.$fragment;
    }

    return $url;
  }

 /**
  * @see sfWebController
  */
  public function redirect($url, $delay = 0, $statusCode = 302)
  {
    if (!$this->context->getRequest()->isCookie())
    {
      $url = $url.'?'.SID;
    }
    parent::redirect($url, $delay, $statusCode);
  }
}

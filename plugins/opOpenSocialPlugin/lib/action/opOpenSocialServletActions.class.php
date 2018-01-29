<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * base actions class for the gadgets and social modules
 *
 * @package    opOpenSocialPlugin
 * @subpackage action
 * @author     Shogo Kawahara <kawahara@tejimaya.net> 
 */
abstract class opOpenSocialServletActions extends sfActions
{
  /**
   * servletExecute
   *
   * @param $servlet A servlet object
   */
  protected function servletExecute($servlet)
  {

    if (!($servlet instanceof HttpServlet) && !($servlet instanceof ApiServlet))
    {
      throw new LogicException();
    }

    $request = $this->getRequest();
    $method = "";
    switch($request->getMethod())
    {
      case sfRequest::GET    : $method = 'doGet';    break;
      case sfRequest::POST   : $method = 'doPost';   break;
      case sfRequest::PUT    : $method = 'doPut';    break;
      case sfRequest::DELETE : $method = 'doDelete';
    }
    if (is_callable(array($servlet, $method)))
    {
      $servlet->$method();
    }
    else
    {
      header("HTTP/1.0 405 Method Not Allowed");
      echo "<html><body><h1>405 Method Not Allowed</h1></body></html>";
    }
  }
}

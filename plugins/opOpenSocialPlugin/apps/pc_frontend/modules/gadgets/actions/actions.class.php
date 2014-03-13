<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * gadgets actions.
 *
 * @package    opOpenSocialPlugin
 * @subpackage action
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class gadgetsActions extends opOpenSocialServletActions
{
  /**
   * Execute js action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeJs(sfWebRequest $request)
  {
    $class = new JsServlet();
    self::servletExecute($class);
    return sfView::NONE;
  }

  /**
   * Execute proxy action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeProxy(sfWebRequest $request)
  {
    sfConfig::set('sf_web_debug', false);
    $class = new ProxyServlet();
    self::servletExecute($class);
    return sfView::NONE;
  }

  /**
   * Execute makeRequest action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeMakeRequest(sfWebRequest $request)
  {
    sfConfig::set('sf_web_debug', false);
    $class = new MakeRequestServlet();
    self::servletExecute($class);
    return sfView::NONE;
  }

 /**
  * Executes ifr action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeIfr(sfWebRequest $request)
  {
    $class = new GadgetRenderingServlet();
    self::servletExecute($class);
    return sfView::NONE;
  }

  /**
   * Execute metadata action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeMetadata(sfWebRequest $request)
  {
    $class = new MetadataServlet();
    self::servletExecute($class);
    return sfView::NONE;
  }
}

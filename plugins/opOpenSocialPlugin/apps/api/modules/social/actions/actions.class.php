<?php

require_once 'OAuth.php';

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * social actions.
 *
 * @package    OpenPNE
 * @subpackage opOpenSocialPlugin
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class socialActions extends opOpenSocialServletActions
{
 /**
  * Executes rpc action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeRpc(sfWebRequest $request)
  {
    $class = new JsonRpcServlet();
    return $this->api($class);
  }

 /**
  * Executes rest action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeRest(sfWebRequest $request)
  {
    $class = new DataServiceServlet();
    return $this->api($class);
  }

  protected function api($class)
  {
    sfConfig::set('sf_web_debug', false);

    $opOpenSocialContainerConfig = new opOpenSocialContainerConfig();
    $opOpenSocialContainerConfig->generateAndSave();

    ob_start();
    try
    {
      $this->servletExecute($class);
    }
    catch (SocialSpiException $e)
    {
    }
    $socialData = ob_get_contents();
    ob_end_clean();
    return $this->renderText($socialData);
  }
}

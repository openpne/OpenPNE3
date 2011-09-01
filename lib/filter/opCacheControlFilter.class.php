<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opCacheControlFilter
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara
 */
class opCacheControlFilter extends sfFilter
{
 /**
  * Executes this filter.
  *
  * @param sfFilterChain $filterChain A sfFilterChain instance
  */
  public function execute($filterChain)
  {
    $filterChain->execute();

    if (!headers_sent())
    {
      $response = $this->getContext()->getResponse();
      if (!$response->hasHttpHeader('Pragma') && !$response->hasHttpHeader('Expires') && !$response->hasHttpHeader('Cache-Control'))
      {
        $response->setHttpHeader('Expires', sfWebResponse::getDate(577731600));
        $response->setHttpHeader('LastModified', sfWebResponse::getDate(time()));

        $response->addCacheControlHttpHeader('no-store');
        $response->addCacheControlHttpHeader('no-cache');
        $response->addCacheControlHttpHeader('private');
        $response->addCacheControlHttpHeader('max-age', 0);
        $response->addCacheControlHttpHeader('must-revalidate');
        $response->addCacheControlHttpHeader('post-check', 0);
        $response->addCacheControlHttpHeader('pre-check', 0);

        $response->setHttpHeader('Pragma', 'no-cache');
      }
    }
  }
}

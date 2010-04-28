<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAppendXRDSHeaderFilter
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAppendXRDSHeaderFilter extends sfFilter
{
  public function execute($filterChain)
  {
    $route = $this->context->getRouting()->getCurrentRouteName();

    if ('homepage' === $route)
    {
      $this->context->getResponse()->setHttpHeader('X-XRDS-Location', $this->context->getController()->genUrl('@openid_idpxrds', true));
    }

    $filterChain->execute();
  }
}

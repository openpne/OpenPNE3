<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opRoutingConfigHandler
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opRoutingConfigHandler extends sfRoutingConfigHandler
{
  protected function parse($configFiles)
  {
    $result = parent::parse($configFiles);

    $name = 'symfony_default_routes';
    $options = array(
      'name'  => $name,
    );

    $result[$name] = array('opSymfonyDefaultRouteCollection', array($options));

   return $result;
  }
}

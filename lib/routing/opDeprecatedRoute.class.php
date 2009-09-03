<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opDeprecatedRoute
 *
 * @package    OpenPNE
 * @subpackage routing
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opDeprecatedRoute extends sfRoute
{
  public function matchesUrl($url, $context = array())
  {
    $result = parent::matchesUrl($url, $context);
    if (!$result)
    {
      return $result;
    }

    $message = array(
      'This routing rule is deprecated. Please use other rules instead of this.',
      'priority' => sfLogger::ERR,
    );

    if (sfContext::hasInstance())
    {
      sfContext::getInstance()->getEventDispatcher()->notify(new sfEvent($this, 'application.log', $message));
    }

    return $result;
  }
}

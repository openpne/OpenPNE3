<?php

/**
 * This file is part of the sfImageHelper plugin.
 * (c) 2009 Kousuke Ebihara <ebihara@tejimaya.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class sfImageHandlerUser
{
  static public function listenToMethodNotFound(sfEvent $event)
  {
    if ($event['method'] === 'undeleteFlash')
    {
      self::undeleteFlash($event->getSubject());

      return true;
    }

    return false;
  }

  static protected function undeleteFlash(sfUser $user)
  {
    $attributeHolder = $user->getAttributeHolder();

    if ($names = $attributeHolder->getNames('symfony/user/sfUser/flash'))
    {
      foreach ($names as $name)
      {
        $attributeHolder->remove($name, null, 'symfony/user/sfUser/flash/remove');
      }
    }
  }
}

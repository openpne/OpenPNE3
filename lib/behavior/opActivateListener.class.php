<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opActivateListener extends Doctrine_Record_Listener
{
  public function preDqlSelect(Doctrine_Event $event)
  {
    if (!opActivateBehavior::getEnabled())
    {
      return null;
    }

    $params = $event->getParams();
    $field = $params['alias'].'.is_active';
    $query = $event->getQuery();
    if (!$query->contains($field))
    {
      $query->addWhere($field.' = ? OR '.$field.' IS NULL', array(true));
    }
  }
}

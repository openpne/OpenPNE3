<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opDoctrineEventNotifier
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Eitarow Fukamachi <fukamachi@tejimaya.com>
 */
class opDoctrineEventNotifier extends Doctrine_Record_Listener
{
  protected static function notify($when, $action, $doctrineEvent)
  {
    if (!sfContext::hasInstance())
    {
      return null;
    }

    $dispatcher = sfContext::getInstance()->getEventDispatcher();
    $dispatcher->notify(new sfEvent(null, sprintf('op_doctrine.%s_%s_%s', $when, $action, get_class($doctrineEvent->getInvoker()))));
  }

  public function preSave(Doctrine_Event $event)
  {
    self::notify('pre', 'save', $event);
  }

  public function postSave(Doctrine_Event $event)
  {
    self::notify('post', 'save', $event);
  }

  public function preUpdate(Doctrine_Event $event)
  {
    self::notify('pre', 'update', $event);
  }

  public function postUpdate(Doctrine_Event $event)
  {
    self::notify('post', 'update', $event);
  }

  public function preInsert(Doctrine_Event $event)
  {
    self::notify('pre', 'insert', $event);
  }

  public function postInsert(Doctrine_Event $event)
  {
    self::notify('post', 'insert', $event);
  }

  public function preDelete(Doctrine_Event $event)
  {
    self::notify('pre', 'delete', $event);
  }

  public function postDelete(Doctrine_Event $event)
  {
    self::notify('post', 'delete', $event);
  }

  public function preValidate(Doctrine_Event $event)
  {
    self::notify('pre', 'validate', $event);
  }

  public function postValidate(Doctrine_Event $event)
  {
    self::notify('post', 'validate', $event);
  }
}

<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opDoctrineEvent
 *
 * @package    OpenPNE
 * @subpackage event
 * @author     Shouta Kashiwagi <kashwiagi@openpne.jp>
 */
class opDoctrineEvent extends sfEvent
{
  protected $doctrineEvent;

  public function __construct(Doctrine_Event $event, $when, $action, $parameters = array())
  {
    $invoker = $event->getInvoker();
    $parameters['record'] = $invoker;

    parent::__construct($invoker, sprintf('op_doctrine.%s_%s_%s', $when, $action, get_class($invoker)), $parameters);
    $this->doctrineEvent = $event;
  }

  public function getDoctrineEvent()
  {
    return $this->doctrineEvent;
  }

  public function getSubject()
  {
    return $this['record'];
  }
}

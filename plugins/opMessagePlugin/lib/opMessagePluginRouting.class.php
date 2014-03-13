<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Message routing.
 *
 * @package    OpenPNE
 * @author     Maki TAKAHASHI <maki@jobweb.co.jp>
 */
class opMessagePluginRouting
{
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $routing = $event->getSubject();
    // message list
    $routing->prependRoute('receiveList',
      new sfRoute(
        '/message/receiveList',
        array('module' => 'message', 'action' => 'list', 'type' => 'receive')
      )
    );
    $routing->prependRoute('sendList',
      new sfRoute(
        '/message/sendList',
        array('module' => 'message', 'action' => 'list', 'type' => 'send')
      )
    );
    $routing->prependRoute('draftList',
      new sfRoute(
        '/message/draftList',
        array('module' => 'message', 'action' => 'list', 'type' => 'draft')
      )
    );
    $routing->prependRoute('dustList',
      new sfRoute(
        '/message/dustList',
        array('module' => 'message', 'action' => 'list', 'type' => 'dust')
      )
    );

    //show message
    $routing->prependRoute('readReceiveMessage',
      new sfRoute(
        '/message/read/:id',
        array('module' => 'message', 'action' => 'show', 'type' => 'receive'),
        array('id' => '\d+')
      )
    );
    $routing->prependRoute('readSendMessage',
      new sfRoute(
        '/message/check/:id',
        array('module' => 'message', 'action' => 'show', 'type' => 'send'),
        array('id' => '\d+')
      )
    );
    $routing->prependRoute('readDustMessage',
      new sfRoute(
        '/message/checkDelete/:id',
        array('module' => 'message', 'action' => 'show', 'type' => 'dust'),
        array('id' => '\d+')
      )
    );

    //delete message
    $routing->prependRoute('deleteReceiveMessage',
      new sfRoute(
        '/message/deleteReceiveMessage/:id',
        array('module' => 'message', 'action' => 'delete', 'type' => 'receive'),
        array('id' => '\d+')
      )
    );
    $routing->prependRoute('deleteSendMessage',
      new sfRoute(
        '/message/deleteSendMessage/:id',
        array('module' => 'message', 'action' => 'delete', 'type' => 'send'),
        array('id' => '\d+')
      )
    );
    $routing->prependRoute('deleteDustMessage',
      new sfRoute(
        '/message/deleteComplete/:id',
        array('module' => 'message', 'action' => 'delete', 'type' => 'dust'),
        array('id' => '\d+')
      )
    );
    
    //delete message confirm
    $routing->prependRoute('deleteConfirmDustMessage',
      new sfRoute(
        '/message/deleteConfirm/:id',
        array('module' => 'message', 'action' => 'deleteConfirm', 'type' => 'dust'),
        array('id' => '\d+')
      )
    );
  }
}

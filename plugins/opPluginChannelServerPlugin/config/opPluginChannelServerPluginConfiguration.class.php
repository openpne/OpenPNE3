<?php

/**
 * Copyright 2010 Kousuke Ebihara
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @copyright 2010 Kousuke Ebihara
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

/**
 * The opPluginChannelServerPlugin class.
 *
 * @package    opPluginChannelServerPlugin
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opPluginChannelServerPluginConfiguration extends sfPluginConfiguration
{
  const CONFIG_KEY_PREFIX = 'op_plugin_channel_server_plugin_';

  public function initialize()
  {
    $pathToPlugin = sfConfig::get('sf_plugins_dir').'/opPluginChannelServerPlugin';

    sfToolkit::addIncludePath(array(
      $pathToPlugin.'/lib/vendor/PEAR/',
      $pathToPlugin.'/lib/vendor/ActiveResource/',
    ));

    $this->dispatcher->connect('op_confirmation.list', array($this, 'listJoinConfirmation'));
    $this->dispatcher->connect('op_confirmation.decision', array($this, 'processJoinConfirmation'));
    /*
    $this->dispatcher->connect('op_action.post_execute_friend_link', array('opMessagePluginObserver', 'listenToPostActionEventSendFriendLinkRequestMessage'));
    $this->dispatcher->connect('form.post_configure', array('opMessagePluginObserver', 'injectMessageFormField'));
    */
  }

  public function listJoinConfirmation($event)
  {
    if ('plugin_join' !== $event['category'])
    {
      return false;
    }

    $results = array();

    $list = Doctrine::getTable('PluginMember')
      ->getJoinRequests(sfContext::getInstance()->getUser()->getMemberId());

    foreach ($list as $v)
    {
      $results[] = array(
        'id'    => $v->id,
        'image' => array(
          'url'  => $v->Member->getImageFileName(),
          'link' => '@member_profile?id='.$v->Member->id,
        ),
        'list' => array(
          '%nickname%' => array(
            'text' => $v->Member->name,
            'link' => '@member_profile?id='.$v->Member->id,
          ),
          'Plugin' => array(
            'text' => $v->Package->name,
            'link' => '@package_home?name='.$v->Package->name,
          ),
        ),
      );
    }

    $event->setReturnValue($results);

    return true;
  }

  public function processJoinConfirmation($event)
  {
    if ('plugin_join' !== $event['category'])
    {
      return false;
    }

    opActivateBehavior::disable();

    $obj = Doctrine::getTable('PluginMember')->find($event['id']);
    if ($event['is_accepted'])
    {
      $obj->is_active = true;
      $obj->save();

      $event->setReturnValue('Accepted');
    }
    else
    {
      $obj->delete();

      $event->setReturnValue('Rejected');
    }

    opActivateBehavior::enable();

    return true;
  }
}

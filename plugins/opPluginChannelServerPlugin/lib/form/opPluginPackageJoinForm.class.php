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
*/

/**
 * Plugin release from
 *
 * @package    opPluginChannelServerPlugin
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opPluginPackageJoinForm extends BaseForm
{
  protected $package;

  public function configure()
  {
    $this->widgetSchema->setNameFormat('plugin_join[%s]');
  }

  public function setPluginPackage($package)
  {
    $this->package = $package;
  }

  public function injectMessageField()
  {
    // temporary disable for opMessagePlugin bug
    /*
    $this
      ->setWidget('message', new sfWidgetFormTextarea())
      ->setValidator('message', new opValidatorString(array('rtrim' => true, 'required' => false)))
      ->getWidgetSchema()->setLabel('message', 'Message(Arbitrary)')
    ;
    */
  }

  public function send()
  {
    $pluginMember = Doctrine::getTable('PluginMember')->create(array(
      'member_id' => sfContext::getInstance()->getUser()->getMemberId(),
      'position'  => 'developer',
    ));

    $this->package->PluginMember[] = $pluginMember;
    $this->package->save();

    if (opPlugin::getInstance('opMessagePlugin')->getIsActive())
    {
      // temporary disable for opMessagePlugin bug
    /*
      $sender = new opMessageSender();
      $sender->setToMember(Doctrine::getTable('Member')->find(1))
        ->setSubject(sfContext::getInstance()->getI18N()->__('Join request to plugin developer team'))
        ->setBody($this->getValue('message'))
        ->setMessageType('plugin_join')
        ->setIdentifier($pluginMember->id)
        ->send();
     */
    }
  }
}

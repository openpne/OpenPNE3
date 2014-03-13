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
 * opPluginChannelServerPluginConfigForm
 *
 * @package    opPluginChannelServerPlugin
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opPluginChannelServerPluginConfigForm extends BaseForm
{
  public function configure()
  {
    $this
      ->setWidget('channel_name', new sfWidgetFormInputText())
      ->setValidator('channel_name', new opValidatorString(array('required' => false)))

      ->setWidget('summary', new sfWidgetFormInputText())
      ->setValidator('summary', new opValidatorString(array('required' => false)))

      ->setWidget('suggestedalias', new sfWidgetFormInputText())
      ->setValidator('suggestedalias', new opValidatorString(array('required' => false)))

      ->setWidget('related_redmine_base_url', new sfWidgetFormInputText())
      ->setValidator('related_redmine_base_url', new sfValidatorUrl(array('required' => false)))

      ->setWidget('parent_project_id', new sfWidgetFormInputText())
      ->setValidator('parent_project_id', new sfValidatorInteger(array('required' => false)))

      ->setWidget('user_role_id', new sfWidgetFormInputText())
      ->setValidator('user_role_id', new sfValidatorInteger(array('required' => false)))
    ;

    $this->getWidgetSchema()
      ->setNameFormat('plugin_config[%s]')

      ->setHelp('channel_name', 'In default, it will be accessing server name.')
      ->setHelp('summary', 'In default, it will be channel name.')
      ->setHelp('suggestedalias', 'In default, it will be channel name.')
    ;
  }

  public function save()
  {
    foreach ($this->getValues() as $k => $v)
    {
      $key = opPluginChannelServerPluginConfiguration::CONFIG_KEY_PREFIX.$k;
      $config = Doctrine::getTable('SnsConfig')->retrieveByName($key);
      if (!$config)
      {
        $config = new SnsConfig();
        $config->setName($key);
      }
      $config->setValue($v);
      $config->save();

      if (!$v)
      {
        $config->delete();
      }
    }
  }
}

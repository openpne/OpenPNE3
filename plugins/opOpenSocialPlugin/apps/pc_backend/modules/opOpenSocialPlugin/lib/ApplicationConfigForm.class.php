<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Application Config Form.
 *
 * @package    opOpenSocialPlugin
 * @subpackage form
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class ApplicationConfigForm extends sfForm
{
  public function configure()
  {
    $addApplicationRuleChoices =  Doctrine::getTable('Application')->getAddApplicationRuleChoices();
    $this->setWidgets(array(
      'add_application_rule' => new sfWidgetFormChoice(array('choices' => $addApplicationRuleChoices)),
    ));

    $this->setValidators(array(
      'add_application_rule' => new sfValidatorChoice(array('choices' => array_keys($addApplicationRuleChoices))),
    ));

    $this->setDefaults(array(
      'add_application_rule' => (int)Doctrine::getTable('SnsConfig')->get('add_application_rule', ApplicationTable::ADD_APPLICATION_DENY),
    ));

    $this->widgetSchema->setLabels(array(
      'add_application_rule' => 'Allow the SNS members to add apps',
    ));
    $this->widgetSchema->setNameFormat('application_config[%s]');
  }

  public function save()
  {
    foreach ($this->getValues() as $key => $value)
    {
      $snsConfig = Doctrine::getTable('SnsConfig')->findOneByName($key);
      if (!$snsConfig)
      {
        $snsConfig = new SnsConfig();
        $snsConfig->setName($key);
      }
      $snsConfig->setValue($value);
      $snsConfig->save();
    }
    return true;
  }
}

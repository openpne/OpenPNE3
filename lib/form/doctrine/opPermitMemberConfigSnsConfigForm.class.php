<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opPermitMemberConfigSnsConfig form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opPermitMemberConfigSnsConfigForm extends BaseForm
{
  public function configure()
  {
    $choices = array();

    $categories = sfConfig::get('openpne_member_category');
    $categoryAttributes = sfConfig::get('openpne_member_category_attribute');

    foreach ($categories as $key => $value)
    {
      $caption = $key;
      if (!empty($categoryAttributes[$key]['caption']))
      {
        $caption = $categoryAttributes[$key]['caption'];
      }

      $choices[$key] = $caption;
    }

    $this->setWidgets(array(
      'ignored_sns_config' => new sfWidgetFormSelectMany(array('choices' => $choices)),
    ));

    $this->setValidators(array(
      'ignored_sns_config' => new sfValidatorChoice(array('multiple' => true, 'choices' => array_keys($choices), 'required' => false)),
    ));

    $default = Doctrine::getTable('SnsConfig')->get('ignored_sns_config', array());
    if ($default)
    {
      $default = unserialize($default);
    }
    $this->setDefault('ignored_sns_config', $default);

    $this->widgetSchema->setNameFormat('sns_config[%s]');
  }

  public function save()
  {
    $ignored = (array)$this->getValue('ignored_sns_config');
    Doctrine::getTable('SnsConfig')->set('ignored_sns_config', serialize($ignored));
  }
}

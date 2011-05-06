<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthValidatorMemberConfig
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAuthValidatorMemberConfig extends sfValidatorSchema
{
  /**
   * Constructor.
   *
   * @param array  $options   An array of options
   * @param array  $messages  An array of error messages
   *
   * @see sfValidatorSchema
   */
  public function __construct($options = array(), $messages = array())
  {
    parent::__construct(null, $options, $messages);
  }

  /**
   * Configures this validator.
   *
   * Available options:
   *
   *  * config_name: The configuration name of MemberConfig
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addOption('field_name');
    $this->addOption('allow_empty_value', true);
    $this->addRequiredOption('config_name');
    $this->setMessage('invalid', 'ID is not a valid.');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($values)
  {
    opActivateBehavior::disable();
    $configName = $this->getOption('config_name');
    $fieldName = $this->getOption('field_name');
    if (!$fieldName)
    {
      $fieldName = $configName;
    }

    if (!$this->getOption('allow_empty_value') && empty($values[$fieldName]))
    {
      opActivateBehavior::enable();

      return $values;
    }

    $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndValue($configName, $values[$fieldName]);
    if ($memberConfig)
    {
      $values['member'] = $memberConfig->getMember();
    }

    opActivateBehavior::enable();
    return $values;
  }
}

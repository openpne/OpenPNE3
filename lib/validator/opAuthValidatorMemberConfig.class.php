<?php

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
    $memberConfig = MemberConfigPeer::retrieveByNameAndValue($configName, $values[$configName]);
    if ($memberConfig)
    {
      $values['member'] = $memberConfig->getMember();
    }

    opActivateBehavior::enable();
    return $values;
  }
}

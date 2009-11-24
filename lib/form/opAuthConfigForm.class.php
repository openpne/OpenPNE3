<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthConfigForm represents a form to login.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class opAuthConfigForm extends BaseForm
{
  protected
    $adapter = null;

  /**
   * Constructor.
   *
   * @param opAuthAdapter $adapter    An opAuthAdapter object
   * @param array         $defaults   An array of field default values
   * @param array         $options    An array of options
   * @param string        $CRFSSecret A CSRF secret (false to disable CSRF protection, null to use the global CSRF secret)
   *
   * @see sfForm
   */
  public function __construct(opAuthAdapter $adapter, $defaults = array(), $options = array(), $CSRFSecret = null)
  {
    $this->adapter = $adapter;

    foreach ($this->adapter->getAuthConfigSettings() as $key => $value)
    {
      if (isset($defaults[$key]))
      {
        continue;
      }

      if (isset($value['IsConfig']) && !$value['IsConfig'])
      {
        $defaults[$key] = $value['Default'];
        continue;
      }

      $default = $this->adapter->getAuthConfig($key);
      if (!is_null($default))
      {
        $defaults[$key] = $default;
      }
    }

    parent::__construct($defaults, $options, $CSRFSecret);
  }

  public function setup()
  {
    foreach ($this->adapter->getAuthConfigSettings() as $key => $value)
    {
      if (isset($value['IsConfig']) && !$value['IsConfig'])
      {
        continue;
      }

      $obj = opFormItemGenerator::generateWidget($value);
      $this->setWidget($key, $obj);
      $this->setValidator($key, opFormItemGenerator::generateValidator($value));
    }

    $this->widgetSchema->setNameFormat('auth'.$this->adapter->getAuthModeName().'[%s]');
  }

  public function save()
  {
    foreach ($this->getValues() as $key => $value)
    {
      $this->adapter->setAuthConfig($key, $value);
    }
  }
}

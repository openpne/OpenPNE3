<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialSupportedFieldExport
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opOpenSocialSupportedFieldExport
{
  protected
    $export = null,
    $objectType = array('person', 'address', 'phone');

  protected function getGetterMethodName($key)
  {
    return 'getSupportedFields'.sfInflector::camelize($key);
  }

  protected function isExistProfile($name)
  {
    return (bool)Doctrine::getTable('Profile')->findOneByName($name);
  }

  public function getIsSupportedMethodName($key)
  {
    return 'isSupported'.sfInflector::camelize($key);
  }

  public function __construct(opOpenSocialProfileExport $export)
  {
    $this->export = $export;
  }

  public function __call($name, $arguments)
  {
    if (0 === strpos($name, 'isSupported'))
    {
      $key = substr($name, 11);
      $key = strtolower($key[0]).substr($key, 1, strlen($key));

      if (in_array($key, $this->export->profiles))
      {
        return $this->isExistProfile($this->export->tableToOpenPNE[$key]);
      }
      elseif (in_array($key, $this->export->names))
      {
        return true;
      }
      elseif (in_array($key, $this->export->emails))
      {
        return true;
      }
      elseif (in_array($key, $this->export->images))
      {
        return true;
      }
      elseif (in_array($key, $this->export->configs))
      {
        return true;
      }
    }

    throw new BadMethodCallException(sprintf('Unknown method %s::%s', get_class($this), $name));
  }

 /**
  * get supported fields
  *
  * @return array
  */
  public function getSupportedFields()
  {
    $result = array();
    foreach ($this->objectType as $name)
    {
      $methodName = $this->getGetterMethodName($name);
      $r = $this->$methodName();
      if ($r !== null)
      {
        $result[$name] = $r;
      }
    }

    return $result;
  }

  /**
   * get supported fields of opensocial.Person
   *
   * @return array
   */
  public function getSupportedFieldsPerson()
  {
    $result = array();
    foreach ($this->export->tableToOpenPNE as $k => $v)
    {
      $methodName = $this->getIsSupportedMethodName($k);
      $r = $this->$methodName();
      if ($r === true)
      {
        $result[] = $k;
      }
      elseif (is_string($r))
      {
        $result[] = $r;
      }
    }
    $result[] = 'hasApp';

    return $result;
  }

 /**
  * get supported fields of opensocial.Address
  *
  * @return array
  */
  public function getSupportedFieldsAddress()
  {
    $result = array();
    $unstructured = false;
    
    if ($this->isExistProfile('op_preset_region'))
    {
      $result[] = 'region';
      $unstructured = true;
    }
    if ($this->isExistProfile('op_preset_country'))
    {
      $result[] = 'country';
      $unstructured = true;
    }
    if ($this->isExistProfile('op_preset_postal_code'))
    {
      $result[] = 'postalCode';
    }
    if ($unstructured)
    {
      $result[] = 'unstructuredAddress';
    }

    return count($result) ? $result : null;
  }

 /**
  * get supported fields of opensocial.Phone
  *
  * @return array
  */
  public function getSupportedFieldsPhone()
  {
    if ($this->isSupportedPhoneNumbers())
    {
      return array('number');
    }
    return null;
  }

  public function isSupportedAddresses()
  {
    return $this->getSupportedFieldsAddress() ? true : false;
  }

  public function isSupportedBirthday()
  {
    if ($this->isExistProfile('op_preset_birthday'))
    {
      return 'dateOfBirth';
    }
    return false;
  }

  public function isSupportedAge()
  {
    return (bool)$this->isSupportedBirthday();
  }
}

<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opProfileImport
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opProfileImport
{
  public
    $member = null,
    $tableToOpenPNE = array(),
    $profiles = array(),
    $names = array(),
    $emails = array(),
    $images = array(),
    $configs = array();

  public function setData($data)
  {
    $result = array();
    $list = array_flip($this->tableToOpenPNE);

    foreach ($list as $k => $v)
    {
      $methodName = $this->getSetterMethodName($k);
      $result[$k] = $this->$methodName($data, $v);
    }

    return $result;
  }

  public function getSupportedProfiles()
  {
    return array_keys($this->tableToOpenPNE);
  }

  protected function getSetterMethodName($key)
  {
    return 'set'.sfInflector::camelize($key);
  }

  public function __call($name, $arguments)
  {
    if (0 === strpos($name, 'set'))
    {
      $data = $arguments[0];
      $key = $arguments[1];

      if (in_array($key, $this->profiles))
      {
        if (empty($data[$key]))
        {
          return null;
        }

        $this->setMemberProfile($this->member, $this->tableToOpenPNE[$key], array_shift($data[$key]));
      }
      elseif (in_array($key, $this->names))
      {
        $nickname = $this->getValue($data, 'namePerson/friendly');
        if (!$nickname)
        {
          $nickname = $this->getValue($data, 'namePerson');
        }
        $this->member->setName(array_shift($nickname));
      }
      elseif (in_array($key, $this->emails))
      {
        $email = $this->getValue($data, 'contact/email');
        if (!$email)
        {
          return null;
        }

        $email = array_shift($email);

        if (opToolkit::isMobileEmailAddress($email))
        {
          $this->member->setConfig('mobile_address', $email);
        }
        else
        {
          $this->member->setConfig('pc_address', $email);
        }
      }
      elseif (in_array($key, $this->images))
      {
        return null;
      }
      elseif (in_array($key, $this->configs))
      {
        if (empty($data[$key]))
        {
          return null;
        }

        $this->member->setConfig($this->tableToOpenPNE[$key], array_shift($data[$key]));
      }
      else
      {
        throw new BadMethodCallException(sprintf('Unknown method %s::%s', get_class($this), $name));
      }
    }
    else
    {
      throw new BadMethodCallException(sprintf('Unknown method %s::%s', get_class($this), $name));
    }
  }

  public function setMemberProfile($member, $name, $value)
  {
    $profile = Doctrine::getTable('Profile')->retrieveByName($name);
    if (!$profile)
    {
      return null;
    }

    $memberProfile = new MemberProfile();
    $memberProfile->setMemberId($member->id);
    $memberProfile->setProfileId($profile->id);
    $memberProfile->setValue($value);
    $memberProfile->save();
  }

  public function getValue($data, $name)
  {
    if (empty($data[$name]))
    {
      return null;
    }

    return $data[$name];
  }
}

<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialProfileExport
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opOpenSocialProfileExport extends opProfileExport
{
  protected
    $viewer = null,
    $supportedFieldExport = null;

  public $tableToOpenPNE = array(
    'displayName'    => 'name',
    'nickname'       => 'name',
    'thumbnailUrl'   => 'image',
    'profileUrl'     => 'profile_url',
    'addresses'      => 'addresses',
    'aboutMe'        => 'op_preset_self_introduction',
    'gender'         => 'op_preset_sex',
    'age'            => 'age',
    'phoneNumbers'   => 'op_preset_telephone_number',
    'birthday'       => 'op_preset_birthday',
    'languagesSpoken'=> 'language',
  );

  public $profiles = array(
    'addresses',
    'aboutMe',
    'gender',
    'age',
    'phoneNumbers',
    'birthday',
  );

  public $names = array(
    'displayName',
    'nickname',
  );

  public $images = array(
    'thumbnailUrl',
  );

  public $configs = array(
    'profileUrl',
    'languagesSpoken',
  );

  public $forceFields = array(
    'displayName',
    'profileUrl',
    'thumbnailUrl',
  );

  protected function getProfile($name)
  {
    // for bug of OpenPNE3.1.3 <=
    if (!Doctrine::getTable('Profile')->findOneByName($name))
    {
      return '';
    }

    $profile = $this->member->getProfile($name);

    if (!$profile)
    {
      return '';
    }

    if (null !== $this->viewer)
    {
      if (!$profile->isViewable($this->viewer->getId()))
      {
        return '';
      }
    }
    else
    {
      if ($profile->getPublicFlag() !== 0)
      {
        return '';
      }
    }

    return (string)$profile;
  }

  public function __call($name, $arguments)
  {
    if (0 === strpos($name, 'get'))
    {
      $key = substr($name, 3);
      $key = strtolower($key[0]).substr($key, 1, strlen($key));

      if (in_array($key, $this->profiles))
      {
        return (string)$this->getProfile($this->tableToOpenPNE[$key]);
      }
      elseif (in_array($key, $this->names))
      {
        return $this->member->getName();
      }
      elseif (in_array($key, $this->emails))
      {
        return $this->member->getEmailAddress();
      }
      elseif (in_array($key, $this->images))
      {
        return $this->getMemberImageURI();
      }
      elseif (in_array($key, $this->configs))
      {
        return $this->member->getConfig($this->tableToOpenPNE[$key]);
      }
    }

    throw new BadMethodCallException(sprintf('Unknown method %s::%s', get_class($this), $name));
  }

  /**
   * get profile datas
   *
   * @prams array $allowed
   * @return array
   */
  public function getData($allowed = array())
  {
    $result = array();
    $allowed = array_merge($this->forceFields, $allowed);
    $isBlock = false;

    // check access block
    if ($this->viewer)
    {
      $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($this->member->getId(), $this->viewer->getId());

      if ($relation && $relation->getIsAccessBlock())
      {
        $isBlock = true;
      }
    }

    foreach ($this->tableToOpenPNE as $k => $v)
    {
      $checkSupportMethodName = $this->getSupportedFieldExport()->getIsSupportedMethodName($k);
      if (in_array($k, $allowed) && $this->getSupportedFieldExport()->$checkSupportMethodName())
      {
        if ($isBlock)
        {
          $result[$k] = '';
        }
        else
        {
          $methodName = $this->getGetterMethodName($k);
          $result[$k] = $this->$methodName();
        }
      }
    }

    return $result;
  }

  public function setViewer(Member $member)
  {
    $this->viewer = $member;
  }

  public function getViewer()
  {
    return $this->viewer;
  }

  public function getSupportedFieldExport()
  {
    if ($this->supportedFieldExport === null)
    {
      $this->supportedFieldExport = new opOpenSocialSupportedFieldExport($this);
    }
    return $this->supportedFieldExport;
  }

  public function getSupportedFields()
  {
    return $this->getSupportedFieldExport()->getSupportedFields();
  }

  public function getAddresses()
  {
    $result = array();
    $unstructured = array();
    $region = $this->getProfile('op_preset_region');
    if ($region)
    {
      $result['region'] = $region;
      $unstructured[] = $region;
    }

    $country = $this->getProfile('op_preset_country');
    if ($country)
    {
      $result['country'] = $country;
      $unstructured[] = $country;
    }

    $postalCode = $this->getProfile('op_preset_postal_code');
    if ($postalCode)
    {
      $result['postalCode'] = $postalCode;
    }

    if (count($unstructured))
    {
      $result['formatted'] = implode(',', $unstructured);
    }

    return array($result);
  }

  public function getGender()
  {
    $sex = $this->getProfile('op_preset_sex');
    if (!$sex)
    {
      return '';
    }
    return ('Man' == $sex ? 'male' : 'female');
  }

  public function getBirthday()
  {
    $birth = $this->getProfile('op_preset_birthday');
    if (!$birth)
    {
      return '';
    }
    $age = $this->getAge();
    if (!$age)
    {
      // age of the person is private
      return date('0000/m/d', strtotime($birth));
    }

    return date('Y/m/d', strtotime($birth));
  }

  public function getAge()
  {
    if (method_exists($this->member, 'getAge'))
    {
      // for OpenPNE3.4 <=

      if (null !== $this->viewer && $this->viewer->getId() !== $this->member->getId())
      {
        $age = $this->member->getAge(true, $this->viewer->getId());
      }
      else
      {
        $age = $this->member->getAge(false, $this->member->getId());
      }
    }
    else
    {
      // for OpenPNE3.4 >
      $birth = $this->getProfile('op_preset_birthday');
      if (!$birth)
      {
        return '';
      }

      $age = (int)(((int)date('Ymd') - (int)date('Ymd', strtotime($birth))) / 10000);
    }

    return (false !== $age) ? $age : '';
  }

  public function getPhoneNumbers()
  {
    $number = $this->getProfile('op_preset_telephone_number');

    return array(array('value' => $number));
  }

  public function getThumbnailUrl()
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Asset', 'sfImage'));

    if ($this->member->getImage())
    {
      return sf_image_path($this->member->getImageFileName(), array(), true);
    }
    return '';
  }

  public function getProfileUrl()
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('opUtil'));
    return app_url_for('pc_frontend', 'member/profile?id='.$this->member->getId(), true);
  }

  public function getLanguagesSpoken()
  {
    $language = $this->member->getConfig('language');
    return substr($language, 0, strpos($language, '_'));
  }
}

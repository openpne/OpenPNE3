<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opProfileExport
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opProfileExport
{
  public
    $member = null,
    $tableToOpenPNE = array(),
    $profiles = array(),
    $names = array(),
    $emails = array(),
    $images = array(),
    $configs = array();

  public function getData($allowed = array())
  {
    $result = array();

    foreach ($this->tableToOpenPNE as $k => $v)
    {
      if (!in_array($v, $allowed))
      {
        continue;
      }

      $methodName = $this->getGetterMethodName($k);
      $result[$k] = $this->$methodName($k);
    }

    return $result;
  }

  protected function getGetterMethodName($key)
  {
    return 'get'.sfInflector::camelize($key);
  }

  protected function getMemberImageURI($options = array())
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url', 'opUtil', 'sfImage', 'Asset', 'Tag'));

    return sf_image_path($this->member->getImageFileName(), $options, true);
  }

  public function __call($name, $arguments)
  {
    if (0 === strpos($name, 'get'))
    {
      $key = $arguments[0];

      if (in_array($key, $this->profiles))
      {
        return (string)$this->member->getProfile($this->tableToOpenPNE[$key]);
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
}

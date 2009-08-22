<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opProfileExchange
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opProfileExchange
{
  protected
    $classNamePrefix = 'op',

    $import = null,
    $export = null,
    $member = null;

  public function __construct($exchangeName, Member $member = null)
  {
    $importClassName = $this->getClassNameBase($exchangeName).'Import';
    $exportClassName = $this->getClassNameBase($exchangeName).'Export';

    $this->import = new $importClassName();
    $this->export = new $exportClassName();

    if ($member)
    {
      $this->setMember($member);
    }
  }

  public function setMember(Member $member)
  {
    $this->member = $member;
    $this->import->member = $member;
    $this->export->member = $member;
  }

  protected function getClassNameBase($exchangeName)
  {
    return $this->classNamePrefix.sfInflector::camelize($exchangeName).'Profile';
  }

  public function getImportSupportedProfiles()
  {
    return $this->import->getSupportedProfiles();
  }

  public function __call($name, $arguments)
  {
    if (0 === strpos($name, 'getImport') || 0 === strpos($name, 'setImport'))
    {
      return call_user_func_array(array($this->import, str_replace('Import', '', $name)), $arguments);
    }
    elseif (0 === strpos($name, 'getExport') || 0 === strpos($name, 'setExport'))
    {
      return call_user_func_array(array($this->export, str_replace('Export', '', $name)), $arguments);
    }
    elseif (0 === strpos($name, 'get'))
    {
      return call_user_func_array(array($this->export, $name), $arguments);
    }
    elseif (0 === strpos($name, 'set'))
    {
      return call_user_func_array(array($this->import, $name), $arguments);
    }

    throw new BadMethodCallException(sprintf('Unknown method %s::%s', get_class($this), $name));
  }
}

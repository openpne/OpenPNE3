<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class ProfileTable extends Doctrine_Table
{
  const PUBLIC_FLAG_SNS = 1;
  const PUBLIC_FLAG_FRIEND = 2;
  const PUBLIC_FLAG_PRIVATE = 3;
  const PUBLIC_FLAG_WEB = 4;

  protected $publicFlags = array(
    self::PUBLIC_FLAG_WEB     => 'All Users on the Web',
    self::PUBLIC_FLAG_SNS     => 'All Members',
    self::PUBLIC_FLAG_FRIEND  => '%my_friend%',
    self::PUBLIC_FLAG_PRIVATE => 'Private',
  );

  protected $nameByIds = array();

  public function getPublicFlags()
  {
    return array_map(array(sfContext::getInstance()->getI18N(), '__'), $this->publicFlags);
  }

  public function getPublicFlag($flag)
  {
    return sfContext::getInstance()->getI18N()->__($this->publicFlags[$flag]);
  }

  public function retrievesAll()
  {
    return $this->createQuery()->orderBy('sort_order')->execute();
  }

  public function retrieveByIsDispRegist()
  {
    return $this->createQuery()
      ->where('is_disp_regist = ?', true)
      ->orderBy('sort_order')
      ->execute();
  }

  public function retrieveByIsDispConfig()
  {
    return $this->createQuery()
      ->where('is_disp_config = ?', true)
      ->orderBy('sort_order')
      ->execute();
  }

  public function retrieveByIsDispSearch()
  {
    return $this->createQuery()
      ->where('is_disp_search = ?', true)
      ->orderBy('sort_order')
      ->execute();
  }

  // TODO: Use findOneByName
  public function retrieveByName($name)
  {
    return $this->createQuery()
      ->where('name = ?', $name)
      ->fetchOne();
  }

  public function getMaxSortOrder()
  {
    $result = $this->createQuery()
      ->orderBy('sort_order DESC')
      ->fetchOne();

    if ($result)
    {
      return (int)$result->getSortOrder();
    }

    return 0;
  }

  public function getProfileNameById($name)
  {
    if (isset($this->nameByIds[$name]))
    {
      return $this->nameByIds[$name];
    }

    $profile = $this->createQuery()
      ->select('id')
      ->where('name = ?', $name)
      ->fetchOne(array(), Doctrine::HYDRATE_NONE);

    if ($profile)
    {
      $this->nameByIds[$name] = $profile[0];
    }
    else
    {
      $this->nameByIds[$name] = null;
    }

    return $this->nameByIds[$name];
  }
}

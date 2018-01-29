<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * ApplicationSearchForm
 *
 * @package    opOpenSocialPlugin
 * @subpackage form
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */

class ApplicationSearchForm extends sfForm
{
  static protected
    $sortOrderChoices = array(
      'created_at_desc' => 'Newest',
      'created_at'      => 'Oldest',
      'users_desc'      => 'Users',
    );

  public function __construct($defaults = array(), $options = array(), $CSRFProtection = false)
  {
    parent::__construct($defaults, $options, $CSRFProtection);
  }

  public function configure()
  {
    $this->setWidgets(array(
      'keyword'    => new sfWidgetFormInput(),
      'order_by'   => new sfWidgetFormChoice(array(
        'choices' => array_map(array(sfContext::getInstance()->getI18N(), '__'), self::$sortOrderChoices)
      ))
    ));
    $this->setValidators(array(
      'keyword'    => new opValidatorSearchQueryString(array('required' => false)),
      'order_by'   => new sfValidatorPass(),
    ));
    $this->setDefaults(array('order_by' => 'created_at_desc'));
    $this->widgetSchema->setNameFormat('application[%s]');
  }

  public function getPager($page = 1, $size = 20, $isCheckActive = false)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    $query = Doctrine::getTable('Application')->createQuery('a')
      ->leftJoin('a.Translation t');

    if ($isCheckActive)
    {
      $query->where('a.is_active = ?', true);
    }

    $keywords = $this->getValue('keyword');
    if ($keywords)
    {
      if (!is_array($keywords))
      {
        $keywords = array($keywords);
      }
      foreach ($keywords as $keyword)
      {
        $query->addWhere('t.title LIKE ?', '%'.$keyword.'%');
      }
    }
    $orderBy = $this->getValue('order_by');
    if (!$orderBy)
    {
      $orderBy = 'created_at_desc';
    }

    $isDesc = false;
    if (preg_match('/_desc$/', $orderBy))
    {
      $isDesc  = true;
      $orderBy = substr($orderBy, 0, strlen($orderBy) - 5);
    }

    $orderByDql = null;
    switch ($orderBy)
    {
      case 'created_at':
        $orderByDql = 't.created_at';
        break;
      case 'users':
        $subquery = Doctrine::getTable('MemberApplication')->createQuery('ma')
          ->select('COUNT(*)')
          ->where('ma.application_id = a.id');
        $query->select('*, ('.$subquery->getDql().') AS users');
        $orderByDql = 'users';
    }

    if ($orderByDql)
    {
      if ($isDesc)
      {
        $orderByDql .= ' DESC';
      }
      $query->orderBy($orderByDql);
    }
    
    $pager = new sfDoctrinePager('Application', $size);
    $pager->setQuery($query);
    $pager->getPage($page);
    $pager->init();
    return $pager;
  }
}

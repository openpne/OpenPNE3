<?php

/**
 * SnsTerm form.
 *
 * @package    form
 * @subpackage SnsTerm
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class SnsTermForm extends BaseSnsTermForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'name'        => new sfWidgetFormInputHidden(),
      'application' => new sfWidgetFormInputHidden(),
    ));

    $this->embedI18n(array(sfContext::getInstance()->getUser()->getCulture()));
  }

/*
  public function configure()
  {
    $this->setWidgets(array(
      'id' => new sfWidgetFormInputHidden(),
      'uri' => new sfWidgetFormInputText(),
      'type' => new sfWidgetFormInputHidden(),
    ));

    $this->widgetSchema->setNameFormat('nav[%s]');
    $this->embedI18n(array('ja_JP'));

    unset($this['sort_order'], $this['created_at'], $this['updated_at']);
  }

  public function updateObject($values = null)
  {
    $nav = parent::updateObject($values);

    if ($this->isNew())
    {
      $maxSortOrder = 0;

      $navs = Doctrine::getTable('Navigation')->retrieveByType($nav->getType());

      $finalNav = $navs->getLast();

      if ($finalNav)
      {
        $maxSortOrder = $finalNav->getSortOrder();
      }

      $nav->setSortOrder($maxSortOrder + 10);
    }

    return $nav;
  }
  */
}

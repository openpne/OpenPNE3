<?php

/**
 * Navigation form.
 *
 * @package    form
 * @subpackage Navigation
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class NavigationForm extends BaseNavigationForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'id' => new sfWidgetFormInputHidden(),
      'uri' => new sfWidgetFormInput(),
      'type' => new sfWidgetFormInputHidden(),
    ));

    $this->widgetSchema->setNameFormat('nav[%s]');
    $this->embedI18n(sfConfig::get('op_supported_languages'));

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
}

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
    $this->embedI18n(array('ja_JP'));

    unset($this['created_at'], $this['updated_at']);
  }

  public function updateObject($values = null)
  {
    $nav = parent::updateObject($values);

    if (!$nav->getSortOrder())
    {
      $maxSortOrder = 0;

      $navs = Doctrine::getTable('Navigation')->retrieveByType($nav->getType());
      $finalNav = array_pop($navs);
      if ($finalNav)
      {
        $maxSortOrder = $finalNav->getSortOrder();
      }

      $nav->setSortOrder($maxSortOrder + 10);
    }

    return $nav;
  }
}

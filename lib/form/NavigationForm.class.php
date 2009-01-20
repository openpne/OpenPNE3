<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Navigation form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
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
  }

  public function updateObject($values = null)
  {
    $nav = parent::updateObject($values);

    if (!$nav->getSortOrder())
    {
      $maxSortOrder = 0;

      $navs = NavigationPeer::retrieveByType($nav->getType());
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

<?php

/**
 * Navi form.
 *
 * @package    form
 * @subpackage navi
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class NaviForm extends BaseNaviForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'id' => new sfWidgetFormInputHidden(),
      'uri' => new sfWidgetFormInput(),
      'type' => new sfWidgetFormInputHidden(),
    ));

    $this->widgetSchema->setNameFormat('navi[%s]');
    $this->embedI18n(array('ja_JP'));
  }

  public function updateObject($values = null)
  {
    $navi = parent::updateObject($values);

    if (!$navi->getSortOrder())
    {
      $maxSortOrder = 0;

      $navis = NaviPeer::retrieveByType($navi->getType());
      $finalNavi = array_pop($navis);
      if ($finalNavi)
      {
        $maxSortOrder = $finalNavi->getSortOrder();
      }

      $navi->setSortOrder($maxSortOrder + 10);
    }

    return $navi;
  }
}

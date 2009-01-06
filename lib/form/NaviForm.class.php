<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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

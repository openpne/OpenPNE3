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
 * A view for OpenPNE.
 *
 * @package    OpenPNE
 * @subpackage view
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */

class sfOpenPNEView extends sfPHPView
{
  public $customizeConditions = array(
    'category' => array(
      'all' => array(),
    ),
    'parts' => array(
      'all' => array(),
    ),
    'target' => array(
      'all' => array(),
    ),
  );

  public $customizeTemplates = array();

  protected $customizeComponents = array();

  /**
   * Sets the customize.
   *
   * @param string $attributeName  A template attribute name
   * @param string $moduleName     A module name
   * @param string $templateName   A template name
   * @param array  $categoryNames  Category names
   * @param array  $partsNames     Parts names
   * @param array  $targetNames    Target names
   * @param bool   $isComponent
   */
  public function setCustomize($attributeName, $moduleName, $templateName, $categoryNames, $partsNames, $targetNames, $isComponent = false)
  {
    if (empty($categoryNames))
    {
      $this->customizeConditions['category']['all'][] = $attributeName;
    }
    else
    {
      foreach ($categoryNames as $categoryName)
      {
        $this->customizeConditions['category'][$categoryName][] = $attributeName;
      }
    }

    if (empty($partsNames))
    {
      $this->customizeConditions['parts']['all'][] = $attributeName;
    }
    else
    {
      foreach ($partsNames as $partsName)
      {
        $this->customizeConditions['parts'][$partsName][] = $attributeName;
      }
    }

    if (empty($targetNames))
    {
      $this->customizeConditions['target']['all'][] = $attributeName;
    }
    else
    {
      foreach ($targetNames as $targetName)
      {
        $this->customizeConditions['target'][$targetName][] = $attributeName;
      }
    }

    $this->customizeTemplates[$attributeName] = array($moduleName, $templateName, $isComponent);
  }

  /**
   * Gets the customize.
   *
   * @param array  $categoryName  A category name
   * @param array  $partsName     A parts name
   * @param array  $targetName    A target name
   */
  public function getCustomize($categoryName, $partsName, $targetName)
  {
    $result = array();

    $categoryCustomizes = $this->customizeConditions['category']['all'];
    if ($categoryName && !empty($this->customizeConditions['category'][$categoryName]))
    {
      $categoryCustomizes = array_merge($categoryCustomizes, $this->customizeConditions['category'][$categoryName]);
    }

    $partsCustomizes = $this->customizeConditions['parts']['all'];
    if ($partsName && !empty($this->customizeConditions['parts'][$partsName]))
    {
      $partsCustomizes = array_merge($partsCustomizes, $this->customizeConditions['parts'][$partsName]);
    }

    $targetCustomizes = $this->customizeConditions['target']['all'];
    if ($targetName && !empty($this->customizeConditions['target'][$targetName]))
    {
      $targetCustomizes = array_merge($targetCustomizes, $this->customizeConditions['target'][$targetName]);
    }

    $customizes = array_intersect($categoryCustomizes, $partsCustomizes, $targetCustomizes);
    foreach ($customizes as $customize)
    {
      $result[] = $this->customizeTemplates[$customize];
    }

    return $result;
  }
}

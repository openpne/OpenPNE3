<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opFormItemGenerator generates form items (widgets and validators)
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opFormItemGenerator
{
 /**
  * This method exists only for BC
  */
  public static function arrayKeyCamelize(array $array)
  {
    foreach ($array as $key => $value)
    {
      unset($array[$key]);
      $array[sfInflector::classify($key)] = $value;
    }

    return $array;
  }

  public static function generateWidgetParams($field, $choices = array())
  {
    $params = array();
    $field = self::arrayKeyCamelize($field);

    if ($field['Caption'])
    {
      $params['label'] = $field['Caption'];
    }

    switch ($field['FormType'])
    {
      case 'checkbox' :
      case 'select' :
      case 'radio' :
        $params['choices'] = array_map(array(sfContext::getInstance()->getI18N(), '__'), $choices);
        if (!empty($field['Choices']) && is_array($field['Choices']))
        {
          $params['choices'] = array_map(array(sfContext::getInstance()->getI18N(), '__'), $field['Choices']);
        }
    }

    if (!empty($field['Default']))
    {
      $params['default'] = $field['Default'];
    }

    return $params;
  }

  public static function generateWidget($field, $choices = array())
  {
    $params = self::generateWidgetParams($field, $choices);
    $field = self::arrayKeyCamelize($field);

    switch ($field['FormType'])
    {
      case 'checkbox':
        $obj = new sfWidgetFormSelectCheckbox($params);
        break;
      case 'select':
        $obj = new sfWidgetFormSelect($params);
        break;
      case 'radio':
        $obj = new sfWidgetFormSelectRadio($params);
        break;
      case 'textarea':
        $obj = new sfWidgetFormTextarea($params);
        break;
      case 'rich_textarea':
        $params['config'] = 'theme_advanced_buttons1: "bold, italic, underline, forecolor, hr", theme_advanced_buttons2:"", theme_advanced_buttons3:"", ';
        $params['width'] = '200px';
        $jsPath = 'tiny_mce/tiny_mce';
        sfContext::getInstance()->getResponse()->addJavascript($jsPath);
        $obj = new sfWidgetFormTextareaTinyMCE($params, array('class' => 'tinymce'));
        break;
      case 'password':
        $obj = new sfWidgetFormInputPassword($params);
        break;
      case 'date':
        unset($params['choices']);
        $params['culture'] = sfContext::getInstance()->getUser()->getCulture();
        $params['month_format'] = 'number';
        $obj = new opWidgetFormDate($params);
        break;
      case 'increased_input':
        $obj = new opWidgetFormInputIncreased($params);
        break;
      case 'language_select':
        $languages = sfConfig::get('op_supported_languages');
        $choices = opToolkit::getCultureChoices($languages);
        $obj = new sfWidgetFormChoice(array('choices' => $choices));
        break;
      default:
        $obj = new sfWidgetFormInput($params);
    }

    return $obj;
  }

  public static function generateValidator($field, $choices = array())
  {
    $field = self::arrayKeyCamelize($field);
    $option = array('required' => $field['IsRequired'], 'trim' => $field['IsRequired']);
    if (!$choices && !empty($field['Choices']))
    {
      $choices = array_keys($field['Choices']);
    }

    if ($field['FormType'] === 'checkbox')
    {
      $option['choices'] = $choices;
      $obj = new sfValidatorChoiceMany($option);
      return $obj;
    }
    if ($field['FormType'] === 'select' || $field['FormType'] === 'radio')
    {
      $obj = new sfValidatorChoice(array('choices' => $choices));
      return $obj;
    }

    if ($field['ValueType'] === 'integer' || $field['FormType'] === 'date')
    {
        if (isset($field['ValueMin']))
        {
          $option['min'] = $field['ValueMin'];
        }
        if (isset($field['ValueMax']))
        {
          $option['max'] = $field['ValueMax'];
        }
    }
    else
    {
        if (isset($field['ValueMin']))
        {
          $option['min_length'] = $field['ValueMin'];
        }
        if (isset($field['ValueMax']))
        {
          $option['max_length'] = $field['ValueMax'];
        }
    }

    if ($field['FormType'] === 'date')
    {
      $obj = new opValidatorDate($option);
      return $obj;
    }

    switch ($field['ValueType'])
    {
      case 'email':
        $obj = new sfValidatorEmail($option);
        break;
      case 'pc_email':
        $obj = new opValidatorPCEmail($option);
        break;
      case 'mobile_email':
        $obj = new sfValidatorMobileEmail($option);
        break;
      case 'integer':
        $obj = new sfValidatorInteger($option);
        break;
      case 'regexp':
        $option['pattern'] = $field['ValueRegexp'];
        $obj = new sfValidatorRegex($option);
        break;
      case 'url':
        $obj = new sfValidatorUrl($option);
        break;
      case 'password':
        $obj = new sfValidatorPassword($option);
        break;
      case 'pass':
        $obj = new sfValidatorPass($option);
        break;
      default:
        $obj = new sfValidatorString($option);
    }

    return $obj;
  }

  public static function generateSearchWidget($field, $choices = array())
  {
    $params = self::generateWidgetParams($field, $choices);
    $field = self::arrayKeyCamelize($field);

    switch ($field['FormType'])
    {
      // selection
      case 'checkbox':
      case 'select':
      case 'radio':
        $obj = new sfWidgetFormSelect($params);
        break;
      // doesn't allow searching
      case 'increased_input':
      case 'language_select':
      case 'password':
        $obj = null;
        break;
      // date
      case 'date':
        unset($params['choices']);
        $params['culture'] = sfContext::getInstance()->getUser()->getCulture();
        $params['month_format'] = 'number';
        $params['can_be_empty'] = true;
        $obj = new opWidgetFormDate($params);
        break;
      // text and something else
      default:
        $obj = new sfWidgetFormInput($params);
    }

    return $obj;
  }

  public static function filterSearchQuery($q, $column, $value, $field, $choices = array())
  {
    $field = self::arrayKeyCamelize($field);

    if (!$q)
    {
      $q = new Doctrine_Query();
    }

    if (empty($value))
    {
      return $q;
    }

    switch ($field['FormType'])
    {
      // selection
      case 'checkbox':
      case 'select':
      case 'radio':
        $q->andWhere($column.' = ?', $value);
        break;
      // doesn't allow searching
      case 'increased_input':
      case 'language_select':
      case 'password':
        // pass
        break;
      // text and something else
      default:
        $q->andWhere($column.' LIKE ?', '%'.$value.'%');
    }

    return $q;
  }
}

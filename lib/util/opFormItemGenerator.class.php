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
  protected static $choicesType = array('checkbox', 'select', 'radio');

  public static function generateWidgetParams($field, $choices = array())
  {
    $params = array();

    if ($field['Caption'])
    {
      $params['label'] = $field['Caption'];
    }

    if (in_array($field['FormType'], self::$choicesType))
    {
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

    if (in_array($field['FormType'], self::$choicesType))
    {
      if ($field['FormType'] === 'select')
      {
        if (!$field['IsRequired'])
        {
          $params['choices'] = array('' => sfContext::getInstance()->getI18N()->__('Please Select')) + $params['choices'];
        }
      }
      else
      {
        $params['expanded'] = true;
      }
    }

    switch ($field['FormType'])
    {
      case 'checkbox':
        $obj = new sfWidgetFormChoiceMany($params);
        break;
      case 'select':
        $obj = new sfWidgetFormChoice($params);
        break;
      case 'radio':
        $obj = new sfWidgetFormChoice($params);
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
        if (!$field['IsRequired'])
        {
          $params['can_be_empty'] = true;
        }
        $obj = new opWidgetFormDate($params);
        break;
      case 'increased_input':
        $obj = new opWidgetFormInputIncreased($params);
        break;
      default:
        $obj = new sfWidgetFormInput($params);
    }

    return $obj;
  }

  public static function generateValidator($field, $choices = array())
  {
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
      $option = array('choices' => $choices);
      $option['required'] = $field['IsRequired'];
      $obj = new sfValidatorChoice($option);
      return $obj;
    }

    if ($field['ValueType'] === 'integer' || $field['FormType'] === 'date')
    {
        if (!empty($field['ValueMin']))
        {
          $option['min'] = $field['ValueMin'];
        }
        if (!empty($field['ValueMax']))
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
        $obj = new opValidatorString($option);
    }

    return $obj;
  }

  public static function generateSearchWidget($field, $choices = array())
  {
    $params = self::generateWidgetParams($field, $choices);

    switch ($field['FormType'])
    {
      // selection
      case 'checkbox':
      case 'select':
      case 'radio':
        $obj = new sfWidgetFormChoice($params);
        break;
      // doesn't allow searching
      case 'increased_input':
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

  public static function filterSearchCriteria($c, $column, $value, $field, $choices = array())
  {
    if (!$c)
    {
      $c = new Criteria();
    }

    if (empty($value))
    {
      return $c;
    }

    switch ($field['FormType'])
    {
      // selection
      case 'checkbox':
      case 'select':
      case 'radio':
        if (count($value) == 1)
        {
          $c->add($column, array_shift($value));
        }
        else
        {
          foreach ($value as $item)
          {
            $c->addOr($column, $item);
          }
        }
        break;
      // doesn't allow searching
      case 'increased_input':
      case 'password':
        // pass
        break;
      // text and something else
      default:
        $c->add($column, '%'.$value.'%', Criteria::LIKE);
    }

    return $c;
  }
}

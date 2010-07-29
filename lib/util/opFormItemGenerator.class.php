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

    if (!empty($field['Params']))
    {
      $params = array_merge($params, $field['Params']);
    }

    return $params;
  }

  public static function generateWidget($field, $choices = array())
  {
    $field = self::arrayKeyCamelize($field);
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
        $params['multiple'] = true;
        $obj = new sfWidgetFormChoice($params);
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
        $obj = new opWidgetFormRichTextarea($params);
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
      case 'language_select':
        $languages = sfConfig::get('op_supported_languages');
        $choices = opToolkit::getCultureChoices($languages);
        $obj = new sfWidgetFormChoice(array('choices' => $choices));
        break;
      case 'country_select':
        $info = sfCultureInfo::getInstance(sfContext::getInstance()->getUser()->getCulture());
        $obj = new sfWidgetFormChoice(array('choices' => $info->getCountries()));
        break;
      case 'region_select':
        $list = include(sfContext::getInstance()->getConfigCache()->checkConfig('config/regions.yml'));
        $type = $field['ValueType'];
        if ('string' !== $type && isset($list[$type]))
        {
          $list = $list[$type];
          $list = array_combine($list, $list);
        }
        else
        {
          foreach ($list as $k => $v)
          {
            if ($v)
            {
              $list[$k] = array_combine($v, $v);
            }
          }
        }
        $list = opToolkit::arrayMapRecursive(array(sfContext::getInstance()->getI18N(), '__'), $list);
        $obj = new sfWidgetFormChoice(array('choices' => $list));
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
      $option['multiple'] = true;
      $obj = new sfValidatorChoice($option);
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

        if (1 > (int)$field['ValueMax'] || (isset($field['ValueMin']) && (int)$field['ValueMin'] > (int)$field['ValueMax']))
        {
          unset($option['min_length']);
          unset($option['max_length']);
        }
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
    $field = self::arrayKeyCamelize($field);
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
      case 'language_select':
      // country
      case 'country_select':
        $info = sfCultureInfo::getInstance(sfContext::getInstance()->getUser()->getCulture());
        $params['choices'] = array('' => '') + $info->getCountries();
        $obj = new sfWidgetFormChoice($params);
        break;
      // region
      case 'region_select':
        $list = (array)include(sfContext::getInstance()->getConfigCache()->checkConfig('config/regions.yml'));
        $type = $field['ValueType'];
        if ('string' !== $type && isset($list[$type]))
        {
          $list = $list[$type];
          $list = array_combine($list, $list);
        }
        else
        {
          foreach ($list as $k => $v)
          {
            if ($v)
            {
              $list[$k] = array_combine($v, $v);
            }
          }
        }
        $list = opToolkit::arrayMapRecursive(array(sfContext::getInstance()->getI18N(), '__'), $list);
        $params['choices'] = array('' => '')+ $list;
        $obj = new sfWidgetFormChoice($params);
        break;
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

  public static function filterSearchQuery($q, $column, $value, $field)
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
      case 'date':
        $q->andWhere($column.' LIKE ?', $value);
        break;
      // doesn't allow searching
      case 'increased_input':
      case 'language_select':
      case 'country_select':
        $q->andWhere($column.' = ?', $value);
        break;
      case 'region_select':
        $q->andWhere($column.' = ?', $value);
        break;
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

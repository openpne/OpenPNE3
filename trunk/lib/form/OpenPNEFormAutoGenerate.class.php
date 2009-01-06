<?php

/**
 * OpenPNEFormAutoGenerate is the base class for forms that generate its widgets automatically
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class OpenPNEFormAutoGenerate extends sfForm
{
  protected function generateWidget($field, $choices = array())
  {
    $params = array();

    if ($field['Caption'])
    {
      $params['label'] = $field['Caption'];
    }

    if ($choices)
    {
      $params['choices'] = $choices;
    }
    elseif (!empty($field['Choices']))
    {
      $params['choices'] = $field['Choices'];
    }

    if (!empty($field['Default']))
    {
      $params['default'] = $field['Default'];
    }

    switch ($field['FormType']) {
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
        $params['culture'] = sfContext::getInstance()->getUser()->getCulture();
        $params['month_format'] = 'number';
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

  protected function generateValidator($field, $choices = array())
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
      $obj = new sfValidatorChoice(array('choices' => $choices));
      return $obj;
    }
    if ($field['FormType'] === 'date')
    {
      $obj = new opValidatorDate();
      return $obj;
    }

    if ($field['ValueType'] === 'integer')
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
}

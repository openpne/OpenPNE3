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

    switch ($field['FormType']) {
      case 'checkbox':
        $obj = new sfWidgetFormInputCheckbox($params);
        break;
      case 'select':
        $obj = new sfWidgetFormSelect($params);
        break;
      case 'radio':
        $obj = new sfWidgetFormSelectRadio($params);
        break;
      case 'textarea':
        $obj = new sfWidgetFormTextarea();
        break;
      case 'password':
        $obj = new sfWidgetFormInputPassword();
        break;
      default:
        $obj = new sfWidgetFormInput();
    }

    return $obj;
  }

  protected function generateValidator($field, $choices = array())
  {
    if ($field['FormType'] === 'checkbox' || $field['FormType'] === 'select' || $field['FormType'] === 'radio') {
      $obj = new sfValidatorChoice(array('choices' => $choices));
      return $obj;
    }

    $option = array('required' => $field['IsRequired']);

    if ($field['ValueType'] === 'datetime' || $field['ValueType'] === 'integer')
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
      case 'datetime':
        $obj = new sfValidatorDatetime($option);
        break;
      case 'email':
        $obj = new sfValidatorEmail($option);
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
      default:
        $obj = new sfValidatorString($option);
    }

    return $obj;
  }
}

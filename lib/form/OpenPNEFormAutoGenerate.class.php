<?php

/**
 * OpenPNEFormAutoGenerate is the base class for forms that generate its widgets automatically
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
abstract class OpenPNEFormAutoGenerate extends sfForm
{
  protected function generateWidget($field, $choices = array())
  {
    if ($field['Caption']) {
      $this->widgetSchema->setLabel($field['Name'], $field['Caption']);
    }

    switch ($field['FormType']) {
      case 'checkbox':
        $obj = new sfWidgetFormInputCheckbox(array('choices' => $choices));
        break;
      case 'select':
        $obj = new sfWidgetFormSelect(array('choices' => $choices));
        break;
      case 'radio':
        $obj = new sfWidgetFormSelectRadio(array('choices' => $choices));
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
    if ($field['FormType'] == 'checkbox' || $field['FormType'] == 'select' || $field['FormType'] == 'radio') {
      $obj = new sfValidatorChoice(array('choices' => $choices));
      return $obj;
    }

    $option = array('required' => $field['IsRequired']);
    switch ($field['valueType']) {
      case 'datetime':
        $option['min'] = $field['ValueMin'];
        $option['max'] = $field['ValueMax'];
        $obj = new sfValidatorDatetime($option);
        break;
      case 'email':
        $obj = new sfValidatorEmail($option);
        break;
      case 'integer':
        $option['min'] = $field['ValueMin'];
        $option['max'] = $field['ValueMax'];
        $obj = new sfValidatorInteger($option);
        break;
      case 'regexp':
        $option['pattern'] = $field['ValueRegexp'];
        $obj = new sfValidatorRegex($option);
        break;
      case 'url':
        $obj = new sfValidatorUrl($option);
        break;
      default:
        $option['min_length'] = $field['ValueMin'];
        $option['max_length'] = $field['getValueMax'];
        $obj = new sfValidatorString($option);
    }

    return $obj;
  }
}

<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberConfigTimelineScreenNameForm.
 *
 * @package    opTimelinePlugin
 * @subpackage opTimelinePlugin
 * @author     Shouta Kashiwagi <kashiwagi@tejimaya.com>
 */
class MemberConfigTimelineScreenNameForm extends MemberConfigForm
{

  protected $category = 'timelineScreenName';

  public function setMemberConfigWidget($name)
  {
    $result = parent::setMemberConfigWidget($name);

    if ($name === 'op_screen_name')
    {
      $this->widgetSchema['op_screen_name']->setAttributes(array('size' => 15));
      $this->mergePostValidator(new sfValidatorCallback(array(
        'callback'  => array($this, 'validate'),
      )));
    }
    return $result;
  }

  public function validate($validator, $value)
  {
    if ($value['op_screen_name'] == "" || !preg_match("/^[-._0-9A-Za-z]+$/", $value['op_screen_name']))
    {
      $error = new sfValidatorError($validator, 'invalid');
      throw new sfValidatorErrorSchema($validator, array('op_screen_name' => $error));
    }
    return $value;
  }

  public function save()
  {
    parent::save();
    return true;
  }

}

<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberConfigPublicFlagForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberConfigPublicFlagForm extends MemberConfigForm
{
  protected $category = 'publicFlag';

  public function __construct(Member $member = null, $options = array(), $CSRFSecret = null)
  {
    parent::__construct($member, $options, $CSRFSecret);

    if (Doctrine::getTable('SnsConfig')->get('is_allow_config_public_flag_profile_page'))
    {
      unset($this['profile_page_public_flag']);
    }

    if (!Doctrine::getTable('SnsConfig')->get('is_allow_web_public_flag_age'))
    {
      $widget = $this->widgetSchema['age_public_flag'];

      $choices = $widget->getOption('choices');
      unset($choices[4]);
      $widget->setOption('choices', $choices);

      $this->validatorSchema['age_public_flag']->setOption('choices', array_keys($choices));
    }
  }
}

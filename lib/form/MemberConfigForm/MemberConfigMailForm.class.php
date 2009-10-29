<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberConfigMail form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberConfigMailForm extends MemberConfigForm
{
  protected $category = 'mail';

  public function __construct(Member $member = null, $options = array(), $CSRFSecret = null)
  {
    parent::__construct($member, $options, $CSRFSecret);

    $count = count(opConfig::get('daily_news_day'));

    $i18n = sfContext::getInstance()->getI18N();
    $translated = $i18n->__('[1]Send once a week (%2%)|[2]Send twice a week (%2%)|(2,+Inf]Send %1% times a week (%2%)', array(
      '%1%' => $count,
      '%2%' => implode(',', $this->generateDayList()))
    );

    $choice = new sfChoiceFormat();
    $retval = $choice->format($translated, $count);

    $options = $this->widgetSchema['daily_news']->getOptions();
    $options['choices'][1] = $retval;
    $this->widgetSchema['daily_news']->setOptions($options);
  }

  protected function generateDayList()
  {
    $result = array();

    $dayNames = sfDateTimeFormatInfo::getInstance(sfContext::getInstance()->getUser()->getCulture())->getAbbreviatedDayNames();
    $sun = array_shift($dayNames);
    $dayNames[] = $sun;

    $day = opConfig::get('daily_news_day');
    $config = sfConfig::get('openpne_sns_config');
    $i18n = sfContext::getInstance()->getI18N();

    foreach ($day as $v)
    {
      $result[] = $i18n->__($config['daily_news_day']['Choices'][$v]);
    }

    return $result;
  }
}

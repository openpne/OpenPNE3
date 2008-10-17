<?php

/**
 * sfValidatorMobileEmail validates mobile emails.
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfValidatorMobileEmail extends sfValidatorEmail
{
  protected $mobileAddress = array(
    'docomo.ne.jp',
    'ezweb.ne.jp',
    'softbank.ne.jp',
    'd.vodafone.ne.jp',
    'h.vodafone.ne.jp',
    't.vodafone.ne.jp',
    'c.vodafone.ne.jp',
    'r.vodafone.ne.jp',
    'k.vodafone.ne.jp',
    'n.vodafone.ne.jp',
    'q.vodafone.ne.jp',
    's.vodafone.ne.jp',
    'pdx.ne.jp',
    'di.pdx.ne.jp',
    'dj.pdx.ne.jp',
    'dk.pdx.ne.jp',
    'wm.pdx.ne.jp',
    'disney.ne.jp',
  );

  /**
   * @see sfValidatorRegex
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $filter = create_function('$value', 'return preg_quote($value, \'/\');');
    $str = join('|', array_filter($this->mobileAddress, $filter));

    $this->setOption('pattern', '/^([^@\s]+)@('.$str.')$/i');
  }
}

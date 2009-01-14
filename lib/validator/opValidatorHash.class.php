<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opValidatorHash validates hashes (MD5, sha1).
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opValidatorHash extends sfValidatorRegex
{
  protected $algorithms = array(
    'md5'  => array('length' => 32),
    'sha1' => array('length' => 40),
  );

  /**
   * @see sfValidatorRegex
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addOption('algorithm', 'md5');

    $this->setOption('pattern', '');
  }

  /**
   * @see sfValidatorString
   */
  protected function doClean($value)
  {
    if (!array_key_exists($this->getOption('algorithm'), $this->algorithms))
    {
      throw new LogicException(__CLASS__.' does not support this algorithm');
    }

    $algorithm = $this->algorithms[$this->getOption('algorithm')];
    $this->setOption('pattern', '/^[a-f0-9]{'.$algorithm['length'].'}$/i');

    return parent::doClean($value);
  }
}

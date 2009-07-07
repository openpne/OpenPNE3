<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opValidatorString validates a string.
 * It support to trim double byte spaces.
 * 
 * @package    OpenPNE
 * @subpackage validator
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opValidatorString extends sfValidatorString
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addOption('ltrim', false);
    $this->addOption('rtrim', false);
  }

  public function clean($value)
  {
    $clean = $value;

    if (is_string($clean))
    {
      if ($this->options['trim'])
      {
        $clean = preg_replace('/^[\s　]+/u', '', $clean);
        $clean = preg_replace('/[\s　]+$/u', '', $clean);
      }
      if ($this->options['ltrim'])
      {
        $clean = preg_replace('/^[\s　]+/u', '', $clean);
      }
      if ($this->options['rtrim'])
      {
        $clean = preg_replace('/[\s　]+$/u', '', $clean);
      }
    }

    if ($this->isEmpty($clean))
    {
      if ($this->options['required'])
      {
        throw new sfValidatorError($this, 'required');
      }

      return $this->getEmptyValue();
    }

    return $this->doClean($clean);
  }
}

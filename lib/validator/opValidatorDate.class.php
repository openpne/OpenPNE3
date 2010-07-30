<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opValidatorDate validates a date
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opValidatorDate extends sfValidatorDate
{
  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $obj = null;

    if (is_array($value))
    {
      $obj = $this->convertDateArrayToDateTime($value);
    }
    else if ($regex = $this->getOption('date_format'))
    {
      if (!preg_match($regex, $value, $match))
      {
        throw new sfValidatorError($this, 'bad_format', array('value' => $value, 'date_format' => $this->getOption('date_format_error') ? $this->getOption('date_format_error') : $this->getOption('date_format')));
      }

      $obj = $this->convertDateArrayToDateTime($match);
    }
    else if (!ctype_digit($value))
    {
      $obj = date_create($value);
    }

    if ($obj instanceof DateTime)
    {
      $clean = $obj;
    }
    else
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }

    if ($this->hasOption('max'))
    {
      $max = new DateTime($this->getOption('max'));
      if ($max && $clean->format('U') > $max->format('U'))
      {
        throw new sfValidatorError($this, 'max', array('max' => $max->format($this->getOption('date_format_range_error'))));
      }
    }

    if ($this->hasOption('min'))
    {
      $min = new DateTime($this->getOption('min'));
      if ($min && $clean->format('U') < $min->format('U'))
      {
        throw new sfValidatorError($this, 'min', array('min' => $min->format($this->getOption('date_format_range_error'))));
      }
    }

    if ($this->getOption('with_time'))
    {
      return $clean->format($this->getOption('datetime_output'));
    }
    else
    {
      return $clean->format($this->getOption('date_output'));
    }
  }

  /**
   * Converts an array representing a DateTime object.
   *
   * @param  array $value  An array of date elements
   *
   * @return DateTime
   */
  protected function convertDateArrayToDateTime($value)
  {
    // all elements must be empty or a number
    foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $key)
    {
      if (isset($value[$key]) && !preg_match('#^\d+$#', $value[$key]) && 0 !== (int)$value[$key])
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
    }

    // if one date value is empty, all others must be empty too
    $empties =
      (!isset($value['year']) || !$value['year'] ? 1 : 0) +
      (!isset($value['month']) || !$value['month'] ? 1 : 0) +
      (!isset($value['day']) || !$value['day'] ? 1 : 0);

    if ($empties > 0 && $empties < 3)
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }
    else if (3 == $empties)
    {
      return $this->getEmptyValue();
    }

    if (strlen((int)$value['year']) > 4)
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }

    if (!checkdate(intval($value['month']), intval($value['day']), intval($value['year'])))
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }

    if ($this->getOption('with_time'))
    {
      // if second is set, minute and hour must be set
      // if minute is set, hour must be set
      if (
        $this->isValueSet($value, 'second') && (!$this->isValueSet($value, 'minute') || !$this->isValueSet($value, 'hour'))
        ||
        $this->isValueSet($value, 'minute') && !$this->isValueSet($value, 'hour')
      )
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }

      $clean = new DateTime();
      $clean->setDate(intval($value['year']), intval($value['month']), intval($value['day']));
      $clean->setTime(
        isset($value['hour']) ? intval($value['hour']) : 0,
        isset($value['minute']) ? intval($value['minute']): 0,
        isset($value['second']) ? intval($value['second']): 0
      );
    }
    else
    {
      $clean = new DateTime();
      $clean->setDate(intval($value['year']), intval($value['month']), intval($value['day']));
    }

    return $clean;
  }
}

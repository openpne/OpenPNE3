<?php

class opValidatorChoice extends sfValidatorChoice
{
  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $choices = $this->getChoices();

    if ($this->getOption('multiple'))
    {
      $value = $this->cleanMultiple($value, $choices);
    }
    else
    {
      if (!self::inChoices($value, $choices))
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
    }

    return $value;
  }

  /**
   * Cleans a value when multiple is true.
   *
   * @param  mixed $value The submitted value
   *
   * @return array The cleaned value
   */
  protected function cleanMultiple($value, $choices)
  {
    if (!is_array($value))
    {
      $value = array($value);
    }

    foreach ($value as $v)
    {
      if (!self::inChoices($v, $choices))
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $v));
      }
    }

    $count = count($value);

    if ($this->hasOption('min') && $count < $this->getOption('min'))
    {
      throw new sfValidatorError($this, 'min', array('count' => $count, 'min' => $this->getOption('min')));
    }

    if ($this->hasOption('max') && $count > $this->getOption('max'))
    {
      throw new sfValidatorError($this, 'max', array('count' => $count, 'max' => $this->getOption('max')));
    }

    return $value;
  }

  /**
   * Checks if a value is part of given choices (see bug #4212)
   *
   * @param  mixed $value   The value to check
   * @param  array $choices The array of available choices
   *
   * @return Boolean
   */
  static protected function inChoices($value, array $choices = array())
  {
    foreach ($choices as $choice)
    {
      if ((string) $choice === (string) $value)
      {
        return true;
      }
    }

    return false;
  }
}

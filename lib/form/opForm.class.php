<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opBaseForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opBaseForm extends sfFormSymfony
{
  /**
   * Binds the form with input values.
   *
   * It triggers the validator schema validation.
   *
   * @param array $taintedValues An array of input values
   * @param array $taintedFiles  An array of uploaded files (in the $_FILES or $_GET format)
   */
  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    $this->taintedValues = $taintedValues;
    $this->taintedFiles  = $taintedFiles;
    $this->isBound = true;
    $this->resetFormFields();

    if (is_null($this->taintedValues))
    {
      $this->taintedValues = array();
    }

    if (is_null($this->taintedFiles))
    {
      if ($this->isMultipart())
      {
        throw new InvalidArgumentException('This form is multipart, which means you need to supply a files array as the bind() method second argument.');
      }

      $this->taintedFiles = array();
    }

    try
    {
      $this->doBind(self::deepArrayUnion($this->taintedValues, self::convertFileInformation($this->taintedFiles)));
      $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

      // remove CSRF token
      unset($this->values[self::$CSRFFieldName]);
    }
    catch (sfValidatorErrorSchema $e)
    {
      $this->values = array();
      $this->errorSchema = $e;
    }
  }

  /**
   * Cleans and binds values to the current form.
   *
   * @param array $values A merged array of values and files
   */
  protected function doBind(array $values)
  {
    if (self::$dispatcher)
    {
      $values = self::$dispatcher->filter(new sfEvent($this, 'form.filter_values'), $values)->getReturnValue();
    }

    try
    {
      $this->values = $this->validatorSchema->clean($values);
    }
    catch (sfValidatorError $error)
    {
      if (self::$dispatcher)
      {
        self::$dispatcher->notify(new sfEvent($this, 'form.validation_error', array('error' => $error)));
      }

      throw $error;
    }
  }
}

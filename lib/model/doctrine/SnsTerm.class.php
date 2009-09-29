<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class SnsTerm extends BaseSnsTerm
{
  protected $process = array(
    'withArticle' => false,
    'pluralize' => false,
    'fronting' => false,
    'titleize' => false,
  );

  public function doFronting($string)
  {
    if ('en' === $this->lang)
    {
      $string = strtoupper($string[0]).substr($string, 1);
    }

    return $string;
  }

  public function doTitleize($string)
  {
    if ('en' === $this->lang)
    {
      $words = array_map('ucfirst', explode(' ' ,$string));
      $string = implode(' ', $words);
    }

    return $string;
  }

  public function doPluralize($string)
  {
    if ('en' === $this->lang)
    {
      $string = opInflector::pluralize($string);
    }

    return $string;
  }

  public function doWithArticle($string)
  {
    if ('en' === $this->lang)
    {
      $string = opInflector::getArticle($string).' '.$string;
    }

    return $string;
  }

  public function __toString()
  {
    $value = $this->Translation[$this->lang]->value;

    foreach ($this->process as $k => $v)
    {
      if ($v)
      {
        $method = 'do'.ucfirst($k);
        $value = $this->$method($value);
      }

      $this->process[$k] = false;
    }

    return $value;
  }

  public function __call($name, $args)
  {
    if (isset($this->process[$name]))
    {
      $this->process[$name] = true;

      return $this;
    }

    return parent::__call($name, $args);
  }
}

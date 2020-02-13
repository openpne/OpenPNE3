<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class SnsTermTable extends Doctrine_Table implements ArrayAccess
{
  protected
    $culture = '',
    $application = '',
    $terms = null;

  public function configure($culture = '', $application = '')
  {
    if ($culture)
    {
      $this->culture = $culture;
    }

    if ($application)
    {
      $this->application = $application;
    }
  }

  public function retrieveByName($name)
  {
    $terms = $this->getTerms();
    $fronting = false;

    if (preg_match('/[A-Z]/', $name[0]))
    {
      $fronting = true;
      $name = strtolower($name[0]).substr($name, 1);
    }

    $result = (isset($terms[$name])) ? $terms[$name] : null;

    if ($result && $fronting)
    {
      $result->fronting();
    }

    return $result;
  }

  public function get($name)
  {
    return (!is_null($term = $this->retrieveByName($name))) ? $term : null;
  }

  public function set($name, $value, $culture = '', $application = '')
  {
    if (!$culture)
    {
      $culture = $this->culture;
    }

    if (!$application)
    {
      $application = $this->application;
    }

    if (!$culture || !$application)
    {
      return false;
    }

    $term = $this->createQuery('s')
      ->andWhere('s.name = ?', $name)
      ->andWhere('s.application = ?', $application)
      ->leftJoin('s.Translation t')
      ->andWhere('t.lang = ?', $culture)
      ->fetchOne();

    if (!$term)
    {
      $term = new SnsTerm();
      $term->setName($name);
      $term->setLang($culture);
      $term->setApplication($application);
    }
    $term->setValue($value);

    return $term->save();
  }

  protected function getTerms()
  {
    if (is_null($this->terms))
    {
      $this->terms = array();

      $q = $this->createQuery('s');

      if ($this->application)
      {
        $q->andWhere('s.application = ?', $this->application);
      }
      $q->leftJoin('s.Translation t');

      if ($this->culture)
      {
        $q->andWhere('t.lang = ?', $this->culture);
      }

      foreach ($q->execute() as $term)
      {
        $this->terms[$term->name] = $term;
      }
    }

    return $this->terms;
  }

  public function offsetExists($offset)
  {
    return !is_null($this->get($offset));
  }

  public function offsetGet($offset)
  {
    return $this->get($offset);
  }

  public function offsetSet($offset, $value)
  {
    throw new LogicException('The SnsTermTable class is not writable.');
  }

  public function offsetUnset($offset)
  {
    throw new LogicException('The SnsTermTable class is not writable.');
  }

  public function getOneByApplicationAndName($app, $name, $culture = null)
  {
    return $this->createQuery('s')
      ->andWhere('s.name = ?', $name)
      ->andWhere('s.application = ?', $app)
      ->leftJoin('s.Translation t')
      ->andWhere('t.lang = ?', is_null($culture) ? $this->culture: $culture)
      ->fetchOne();
  }
}

<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opPartsOptionHolder stores variables for view parts
 *
 * @package    OpenPNE
 * @subpackage view
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */

class opPartsOptionHolder extends sfParameterHolder implements ArrayAccess, IteratorAggregate
{
  protected $required = array();

  public function __construct($parameters = array())
  {
    $this->parameters = $parameters;
  }

  public function __get($name)
  {
    return $this->get($name);
  }

  public function __set($name, $value)
  {
    return $this->set($name, $value);
  }

  public function __isset($name)
  {
    return $this->has($name);
  }

  public function __unset($name)
  {
    $this->remove($name);
  }

  public function setDefault($name, $value)
  {
    if (!$this->has($name))
    {
      $this->set($name, $value);
    }
  }

  public function addRequiredOption($name)
  {
    $this->required[] = $name;
  }

  public function getShortRequiredOptions()
  {
    $short = array();

    foreach ($this->required as $name)
    {
      if (!$this->has($name))
      {
        $short[] = $name;
      }
    }

    return $short;
  }

  public function offsetGet($name)
  {
    return $this->get($name);
  }

  public function offsetExists($name)
  {
    return $this->get($name);
  }

  public function offsetSet($name, $value)
  {
    return $this->set($name, $value);
  }

  public function offsetUnset($name)
  {
    return $this->remove($name);
  }

  public function getIterator()
  {
    return new ArrayIterator($this);
  }
}

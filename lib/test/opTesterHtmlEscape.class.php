<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Tests a dynamic value in response might be escaped / unescaped
 *
 * @package    OpenPNE
 * @subpackage test
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class opTesterHtmlEscape extends sfTester
{
  protected
    $response,
    $context;

  const TEST_DATA_TEMPLATE = '<&"\'>%namespace%.%name% ESCAPING HTML TEST DATA';

  public function prepare()
  {
  }

  public function initialize()
  {
    $this->context = $this->browser->getContext();
    $this->response = $this->browser->getResponse();

    $this->context->getConfiguration()->loadHelpers(array('Escaping', 'opUtil'));
  }

  static public function getRawTestData($namespace, $name)
  {
    return strtr(self::TEST_DATA_TEMPLATE, array(
      '%namespace%'  => $namespace,
      '%name%'       => $name,
    ));
  }

  static public function getEscapedTestData($namespace, $name)
  {
    return sfOutputEscaper::escape(ESC_SPECIALCHARS, self::getRawTestData($namespace, $name));
  }

  protected function countTestData($namespace, $name, $isEscaped, $truncateOption = array())
  {
    if ($isEscaped)
    {
      $string = $this->getEscapedTestData($namespace, $name);
    }
    else
    {
      $string = $this->getRawTestData($namespace, $name);
    }

    if ($truncateOption)
    {
      if (!is_array($truncateOption))
      {
        $truncateOption = array();
      }

      $width = isset($truncateOption['width']) ? $truncateOption['width'] : 80;
      $etc = isset($truncateOption['etc']) ? $truncateOption['etc'] : '';
      $rows = isset($truncateOption['rows']) ? $truncateOption['rows'] : 1;

      $string = op_truncate($string, $width, $etc, $rows);
    }

    return substr_count($this->response->getContent(), $string);
  }

  public function countEscapedData($expected, $namespace, $name, $truncateOption = array())
  {
    $this->tester->is($this->countTestData($namespace, $name, true, $truncateOption), $expected, sprintf('%d data of "%s"."%s" are escaped.', $expected, $namespace, $name));

    return $this->getObjectToReturn();
  }

  public function countRawData($expected, $namespace, $name, $truncateOption = array())
  {
    $this->tester->is($this->countTestData($namespace, $name, false, $truncateOption), $expected, sprintf('%d data of "%s"."%s" are raw.', $expected, $namespace, $name));

    return $this->getObjectToReturn();
  }

  public function isAllEscapedData($namespace, $name)
  {
    $isEscaped = !$this->countTestData($namespace, $name, false) && $this->countTestData($namespace, $name, true);

    if ($isEscaped)
    {
      $this->tester->pass(sprintf('all of value of "%s"."%s" are escaped.', $namespace, $name));
    }
    else
    {
      $this->tester->fail(sprintf('there is / are some raw value(s) of "%s"."%s".', $namespace, $name));
    }

    return $this->getObjectToReturn();
  }

  public function isAllRawData($namespace, $name)
  {
    $isRaw = $this->countTestData($namespace, $name, false) && !$this->countTestData($namespace, $name, true);

    if ($isRaw)
    {
      $this->tester->pass(sprintf('all of value of "%s"."%s" are raw.', $namespace, $name));
    }
    else
    {
      $this->tester->fail(sprintf('there is / are some escaped value(s) of "%s"."%s".', $namespace, $name));
    }

    return $this->getObjectToReturn();
  }
}

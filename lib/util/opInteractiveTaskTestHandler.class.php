<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opInteractiveTaskTestHandler provides a way to test interactive-task.
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class opInteractiveTaskTestHandler
{
  public $cli, $t, $lastStatus, $resource = null;

  public $debug = false;

  public $pipes = array();
  public $output = '';

  public function __construct($t)
  {
    $this->t = $t;
    $this->cli = sfToolkit::getPhpCli();
  }

  public function execute($cmd, $inputs = array())
  {
    $symfony = dirname(__FILE__).'/../../symfony';
    if (!is_file($symfony))
    {
      throw new Exception($symfony.' is not found');
    }

    $descriptorspec = array(
      0 => array('pipe', 'r'),  // stdin
      1 => array('pipe', 'w'),  // stdout
      2 => array('pipe', 'w'),  // stderr
    );
    $commandString = $this->cli.' '.$symfony.' '.$cmd;
    $this->resource = proc_open($commandString, $descriptorspec, $this->pipes);

    $this->t->info('Exceuted the specified "'.$commandString.'"');

    return $this;
  }

  public function output($display = false)
  {
    $this->output = fgets($this->pipes[1]);
    if ($display || $this->debug)
    {
      $this->t->info('Output: '.trim($this->output));
    }

    return $this;
  }

  public function outputUntilLiteral($expected)
  {
    $this->outputUntil('/^'.preg_quote($expected, '/').'$/u');

    return $this;
  }

  public function outputUntil($expected, &$matches = array(), $display = false)
  {
    while ($this->output($display))
    {
      if (preg_match($expected, trim($this->output), $matches))
      {
        $this->t->info('Matched with expected output "'.$expected.'"');

        return $this;
      }
    }
  }

  public function getError()
  {
    $error = '';

    while ($l = fgets($this->pipes[2]))
    {
      $error .= trim($l);
    }

    return $error;
  }

  public function skipError()
  {
    $this->getError();

    return $this;
  }

  public function testError($expected, $comment)
  {
    $this->t->is(trim($this->getError()), $expected, $comment);

    return $this;
  }

  public function input($input)
  {
    $this->t->info('Input '.$input);
    fwrite($this->pipes[0], $input.PHP_EOL);

    return $this;
  }

  public function testOutput($expected, $comment = '')
  {
    $this->t->is(trim($this->output), $expected, $comment);

    return $this;
  }

  public function shutdown()
  {
    foreach ($this->pipes as $pipe)
    {
      fclose($pipe);
    }

    proc_close($this->resource);
  }
}

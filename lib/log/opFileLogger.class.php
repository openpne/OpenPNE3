<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * opFileLogger
 *
 * @package    OpenPNE
 * @subpackage log
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opFileLogger extends sfFileLogger
{
  protected $file;

  public function initialize(sfEventDispatcher $dispatcher, $options = array())
  {
    if (isset($options['file']))
    {
      $this->file = $options['file'];
    }
    return parent::initialize($dispatcher, $options);
  }

  /**
   * @see sfFileLogger
   */
  protected function doLog($message, $priority)
  {
    parent::doLog($message, $priority);
    @chmod($this->file, 0666);
  }
}

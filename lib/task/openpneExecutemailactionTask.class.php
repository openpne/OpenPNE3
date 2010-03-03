<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * openpneExecuteMailActionTask
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class openpneExecuteMailActionTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'execute-mail-action';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [openpne:execute-mail-action|INFO] task does things.
Call it with:

  [./symfony openpne:execute-mail-action|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    sfConfig::set('sf_test', true);

    opApplicationConfiguration::registerZend();

    $stdin = file_get_contents('php://stdin');
    $message = new opMailMessage(array('raw' => $stdin));
    opMailRequest::setMailMessage($message);

    opApplicationConfiguration::unregisterZend();

    $configuration = ProjectConfiguration::getApplicationConfiguration('mobile_mail_frontend', 'prod', false);
    $context = sfContext::createInstance($configuration);
    $request = $context->getRequest();

    ob_start();
    $context->getController()->dispatch();
    $retval = ob_get_clean();

    if ($retval)
    {
      $subject = $context->getResponse()->getTitle();
      $to = $message->from;
      $from = $message->to;
      opMailSend::execute($subject, $to, $from, $retval);
    }
  }
}

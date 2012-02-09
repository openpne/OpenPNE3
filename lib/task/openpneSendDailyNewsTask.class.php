<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class openpneSendDailyNewsTask extends opBaseSendMailTask
{
  protected function configure()
  {
    parent::configure();
    $this->namespace        = 'openpne';
    $this->name             = 'send-daily-news';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [openpne:send-birthday-mail|INFO] task does things.
Call it with:

  [php symfony openpne:send-birthday-mail|INFO]
EOF;

    $this->addOptions(
      array(
        new sfCommandOption('app', null, sfCommandOption::PARAMETER_OPTIONAL, 'send to pc or mobile', null),
      )
    );
  }

  protected function execute($arguments = array(), $options = array())
  {
    parent::execute($arguments, $options);

    $expectedOptions = array('pc_frontend', 'mobile_frontend');

    if (isset($options['app']))
    {
      if (in_array($options['app'], $expectedOptions))
      {
        $this->sendDailyNews($options['app']);
      }
      else
      {
        throw new Exception('invalid option');
      }
    }
    else
    {
      $php = $this->findPhpBinary();
      foreach ($expectedOptions as $app)
      {
        exec($php.' '.sfConfig::get('sf_root_dir').'/symfony openpne:send-daily-news --app='.$app);
      }
    }
  }

  private function sendDailyNews($app)
  {
    $isAppMobile = 'mobile_frontend' === $app;
    $dailyNewsName = $isAppMobile ?  'mobileDailyNews' : 'dailyNews';
    $context = sfContext::createInstance($this->createConfiguration($app, 'prod'), $app);

    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName($dailyNewsName);
    $gadgets = $gadgets[$dailyNewsName.'Contents'];

    $targetMembers = Doctrine::getTable('Member')->findAll();
    foreach ($targetMembers as $member)
    {
      $address = $member->getEmailAddress();
      if ($isAppMobile !== opToolkit::isMobileEmailAddress($address))
      {
        continue;
      }

      $dailyNewsConfig = $member->getConfig('daily_news');
      if (null !== $dailyNewsConfig && 0 === (int)$dailyNewsConfig)
      {
        continue;
      }

      if (1 === (int)$dailyNewsConfig && !$this->isDailyNewsDay())
      {
        continue;
      }

      $filteredGadgets = array();
      if ($gadgets)
      {
        foreach ($gadgets as $gadget)
        {
          if ($gadget->isEnabled($member))
          {
            $filteredGadgets[] = array(
              'component' => array('module' => $gadget->getComponentModule(), 'action' => $gadget->getComponentAction()),
              'gadget' => $gadget,
              'member' => $member,
            );
          }
        }
      }

      $params = array(
        'member'  => $member,
        'gadgets' => $filteredGadgets,
        'subject' => $context->getI18N()->__('デイリーニュース'),
        'today'   => time(),
      );

      opMailSend::sendTemplateMail('dailyNews', $address, opConfig::get('admin_mail_address'), $params, $context);
    }
  }

  protected function isDailyNewsDay()
  {
    $day = date('w') - 1;
    if (0 > $day)
    {
      $day = 7;
    }

    return in_array($day, opConfig::get('daily_news_day'));
  }

  private function findPhpBinary()
  {
    if (defined('PHP_BINARY') && PHP_BINARY)
    {
      return PHP_BINARY;
    }

    if (false !== strpos(basename($php = $_SERVER['_']), 'php'))
    {
      return $php;
    }

    // from https://github.com/symfony/Process/blob/379b35a41a2749cf7361dda0f03e04410daaca4c/PhpExecutableFinder.php
    $suffixes = DIRECTORY_SEPARATOR == '\\' ? (getenv('PATHEXT') ? explode(PATH_SEPARATOR, getenv('PATHEXT')) : array('.exe', '.bat', '.cmd', '.com')) : array('');
    foreach ($suffixes as $suffix)
    {
      if (is_executable($php = PHP_BINDIR.DIRECTORY_SEPARATOR.'php'.$suffix))
      {
        return $php;
      }
    }

    if ($php = getenv('PHP_PEAR_PHP_BIN'))
    {
      if (is_executable($php))
      {
        return $php;
      }
    }

    return sfToolkit::getPhpCli();
  }

}

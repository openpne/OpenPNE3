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
  }

  protected function execute($arguments = array(), $options = array())
  {
    parent::execute($arguments, $options);

    sfContext::createInstance($this->createConfiguration('pc_frontend', 'prod'), 'pc_frontend');

    $pcGadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('dailyNews');
    $mobileGadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('mobileDailyNews');

    $targetMembers = Doctrine::getTable('Member')->findAll();
    foreach ($targetMembers as $member)
    {
      $dailyNewsConfig = $member->getConfig('daily_news');
      if (null !== $dailyNewsConfig && 0 === (int)$dailyNewsConfig)
      {
        continue;
      }

      if (1 === (int)$dailyNewsConfig && !$this->isDailyNewsDay())
      {
        continue;
      }
      $address = $member->getEmailAddress();
      $gadgets = $pcGadgets['dailyNewsContents'];
      if (opToolkit::isMobileEmailAddress($address))
      {
        $gadgets = $mobileGadgets['mobileDailyNewsContents'];
      }

      $filteredGadgets = array();
      if ($gadgets)
      {
        foreach ($gadgets as $gadget)
        {
          if ($gadget->isEnabled())
          {
            $filteredGadgets[] = array(
              'component' => array('module' => $gadget->getComponentModule(), 'action' => $gadget->getComponentAction()),
              'gadget' => $gadget,
              'member' => $member,
            );
          }
        }
      }

      $context = $this->getContextByEmailAddress($address);
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
}

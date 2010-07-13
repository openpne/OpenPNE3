<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * NotificationMailTable
 *
 * @package    OpenPNE
 * @subpackage model
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class NotificationMailTable extends Doctrine_Table
{
  protected
    $configs = null;

  public function getDisabledNotificationNames()
  {
    $result = array();

    $list = $this->createQuery()
      ->select('name')
      ->where('is_enabled = ?', false)
      ->execute(array(), Doctrine::HYDRATE_NONE);

    foreach ($list as $v)
    {
      $result[] = $v[0];
    }

    return $result;
  }

  public function fetchTemplate($templateName)
  {
    $object = $this->findOneByName($templateName);
    if (!($object && $object->getTemplate()))
    {
      $configs = $this->getConfigs();

      $template = explode('_', $templateName, 2);
      $sampleSubject = '';
      if (2 === count($template) && isset($configs[$template[0]][$template[1]]))
      {
        $config = $configs[$template[0]][$template[1]];
        if (isset($config['sample']) && is_array($config['sample']) && $config['sample'])
        {
          if (isset($config['sample'][sfDoctrineRecord::getDefaultCulture()]))
          {
            $sample = $config['sample'][sfDoctrineRecord::getDefaultCulture()];
          }
          else
          {
            $sample = $config['sample'][0];
          }

          if ($sample)
          {
            if (is_array($sample))
            {
              if (2 <= count($sample))
              {
                $sampleSubject = $sample[0];
                $sampleBody    = $sample[1];
              }
              else
              {
                $sampleBody = $sample[0];
              }
            }
            else
            {
              $sampleBody = $sample;
            }
          }
        }
      }

      if ($sampleBody)
      {
        $object = new NotificationMail();
        $object->Translation[sfDoctrineRecord::getDefaultCulture()]->title    = $sampleSubject;
        $object->Translation[sfDoctrineRecord::getDefaultCulture()]->template = $sampleBody;
      }
    }

    return $object;
  }

  public function getConfigs()
  {
    if (null === $this->configs)
    {
      $this->configs = include(sfContext::getInstance()->getConfigCache()->checkConfig('config/mail_template.yml'));
    }

    return $this->configs;
  }
}

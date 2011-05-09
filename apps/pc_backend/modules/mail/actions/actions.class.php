<?php

/**
 * mail actions.
 *
 * @package    OpenPNE
 * @subpackage mail
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class mailActions extends sfActions
{
  public function preExecute()
  {
    $this->config = include(sfContext::getInstance()->getConfigCache()->checkConfig('config/mail_template.yml'));
  }

  public function executeConfig(sfWebRequest $request)
  {
    $this->form = new opMailNotificationForm();

    if ($this->request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('notification'));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->getUser()->setFlash('notice', 'Saved.');

        $this->redirect('@mail_config');
      }
    }
  }

  public function executeTemplate(sfWebRequest $request)
  {
    $this->name = $request->getParameter('name', '');
    if ('' !== $this->name)
    {
      $this->forward404Unless(in_array($this->name, $this->generateMailTemplateNames($this->config)));
    }

    $obj = Doctrine::getTable('NotificationMail')->findOneByName($this->name);
    if (!$obj)
    {
      $obj = Doctrine::getTable('NotificationMail')->create(array('name' => $this->name));
    }
    $translation = $obj->Translation['ja_JP'];

    $this->form = new NotificationMailTranslationForm($translation);
    $this->form->updateDefaultsByConfig($this->getMailConfiguration($this->config, $this->name));
    if ($this->request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('notification_mail_translation'));
      if ($this->form->isValid())
      {
        if (!$this->form->getObject()->exists())
        {
          if ($this->form->getObject()->id instanceof Doctrine_Record)
          {
            $this->form->getObject()->id->save();
          }
        }

        $this->form->save();
        $this->getUser()->setFlash('notice', 'Saved.');
        $this->redirect('@mail_template_specified?name='.$this->name);
      }
      $this->getUser()->setFlash('error', (string)$this->form->getErrorSchema());
    }
  }

  protected function getMailConfiguration($config, $name)
  {
    $parts = explode('_', $name, 2);
    if (count($parts) != 2)
    {
      return array();
    }

    return $config[$parts[0]][$parts[1]];
  }

  protected function generateMailTemplateNames($config)
  {
    $result = array();

    foreach ($config as $target => $mails)
    {
      foreach (array_keys($mails) as $template)
      {
        $result[] = $target.'_'.$template;
      }
    }

    return $result;
  }
}

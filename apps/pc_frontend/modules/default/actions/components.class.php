<?php

class defaultComponents extends sfComponents
{
  public function executeGlobalNavi()
  {
    $type = 'insecure_global';
    if ($this->isSecurePage()) {
      $type = 'secure_global';
    }
    $this->navis = NaviPeer::retrieveByType($type);
  }

  public function executeLocalNavi()
  {
    if (!$this->isSecurePage()) {
      return sfView::NONE;
    }

    $context = sfContext::getInstance();
    $module = $context->getActionStack()->getLastEntry()->getModuleName();
    $action = $context->getActionStack()->getLastEntry()->getActionName();

    $type = sfConfig::get('sf_navi_type', sfConfig::get('mod_' . $module . '_default_navi', 'default'));

    $this->navis = NaviPeer::retrieveByType($type);

    if ('default' !== $type)
    {
      $this->naviId = sfConfig::get('sf_navi_id', $context->getRequest()->getParameter('id'));
    }
  }

  private function isSecurePage()
  {
    $context = sfContext::getInstance();
    $action = $context->getActionStack()->getLastEntry()->getActionInstance();
    $credential = $action->getCredential();

    if (sfConfig::get('sf_login_module') === $context->getModuleName() && sfConfig::get('sf_login_action') === $context->getActionName()) {
      return false;
    }

    if (sfConfig::get('sf_secure_module') == $context->getModuleName() && sfConfig::get('sf_secure_action') == $context->getActionName()) {
      return false;
    }

    if (!$action->isSecure()) {
      return false;
    }

    if ((is_array($credential) && !in_array('SNSMember', $credential)) || (is_string($credential) && 'SNSMember' !== $credential)) {
      return false;
    }

    return true;
  }
}

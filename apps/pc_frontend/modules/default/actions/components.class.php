<?php

class defaultComponents extends sfComponents
{
  public function executeGlobalNavi()
  {
    $action = sfContext::getInstance()->getActionStack()->getLastEntry()->getActionInstance();
    $credential = $action->getCredential();
    $type = 'insecure_global';

    if ($this->isSecurePage()) {
      $type = 'secure_global';
    }

    $this->navis = NaviPeer::retrieveByType($type);
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

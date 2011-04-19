<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The base class of the all security user classes for OpenPNE.
 *
 * @package    OpenPNE
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
abstract class opBaseSecurityUser extends sfBasicSecurityUser
{
  const SITE_IDENTIFIER_NAMESPACE = 'OpenPNE/user/opSecurityUser/site_identifier';

  public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {
    if (!isset($options['session_namespaces']))
    {
      $options['session_namespaces'] = array(
        self::SITE_IDENTIFIER_NAMESPACE,
        self::LAST_REQUEST_NAMESPACE,
        self::AUTH_NAMESPACE,
        self::CREDENTIAL_NAMESPACE,
        self::ATTRIBUTE_NAMESPACE,
      );
    }

    parent::initialize($dispatcher, $storage, $options);

    if (!$this->isValidSiteIdentifier())
    {
      // This session is not for this site.
      $this->logout();

      // So we need to clear all data of the current session because they might be tainted by attacker.
      // If OpenPNE uses that tainted data, it may cause limited session fixation attack.
      $this->clearSessionData();

      return null;
    }
  }

  abstract public function logout();

  public function clearSessionData()
  {
    // remove data in storage
    foreach ($this->options['session_namespaces'] as $v)
    {
      $this->storage->remove($v);
    }

    // remove attribtues
    $this->attributeHolder->clear();
  }

  public function isValidSiteIdentifier()
  {
    if (!sfConfig::get('op_check_session_site_identifier', true))
    {
      return true;
    }

    return ($this->generateSiteIdentifier() === $this->storage->read(self::SITE_IDENTIFIER_NAMESPACE));
  }

  public function generateSiteIdentifier()
  {
    $defaultBaseUrl = 'http://example.com';
    $identifier = sfConfig::get('op_base_url', $defaultBaseUrl);

    if (0 === strpos($identifier, $defaultBaseUrl))
    {
      $request = sfContext::getInstance()->getRequest();
      $identifier = $request->getUriPrefix().$request->getRelativeUrlRoot();
    }

    return $identifier;
  }

  public function shutdown()
  {
    $this->storage->write(self::SITE_IDENTIFIER_NAMESPACE, $this->generateSiteIdentifier());

    parent::shutdown();
  }
}

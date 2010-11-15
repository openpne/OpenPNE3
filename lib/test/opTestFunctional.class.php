<?php /** * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * functional test class for OpenPNE.
 *
 * @package    OpenPNE
 * @subpackage test
 * @author     Rimpei Ogawa <ogawa@tejimaya.com>
 */
class opTestFunctional extends sfTestFunctional
{
  protected
    $mobileUserAgent = 'KDDI-CA39 UP.Browser/6.2.0.13.1.5 (FUI) MMP/2.0';

  public function __construct(sfBrowserBase $browser, lime_test $lime = null, $testers = array())
  {
    $testers = array_merge(array(
      'html_escape' => 'opTesterHtmlEscape',
    ), $testers);

    parent::__construct($browser, $lime, $testers);
  }

  public function setMobile($userAgent = null)
  {
    if ($userAgent)
    {
      $this->mobileUserAgent = $userAgent;
    }

    $_SERVER['HTTP_USER_AGENT'] = $this->mobileUserAgent;
    opMobileUserAgent::resetInstance();
  }

  public function login($mailAddress, $password)
  {
    $params = array('authMailAddress' => array(
          'mail_address' => $mailAddress,
          'password'     => $password,
          ));

    return $this->post('/member/login/authMode/MailAddress', $params);
  }

  public function setCulture($culture)
  {
    sfDoctrineRecord::setDefaultCulture($culture);

    return $this->get('/', array('sf_culture' => $culture));
  }

  public function checkDispatch($module, $action)
  {
    return
      $this->with('request')->begin()
        ->isParameter('module', $module)
        ->isParameter('action', $action)
      ->end();
  }

  public function isStatusCode($code)
  {
    return $this->with('response')->isStatusCode($code);
  }

  public function checkCSRF($selectors = array())
  {
    $i18n = $this->getContext()->getI18N();
    $selectors += array(
      $i18n->__('CSRF attack detected.'),
      'csrf[^a-zA-Z]*token:[^a-zA-Z]*'.$i18n->__('Required.'),
    );

    $content = $this->getResponse()->getContent();
    $exists = false;
    foreach ($selectors as $selector)
    {
      if (mb_ereg($selector, $content))
      {
        $exists = true;
        break;
      }
    }
    $this->test()->is($exists, true, 'message about CSRF token exists');

    return $this;
  }
}

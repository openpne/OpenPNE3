<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PluginApplicationTable
 *
 * @package    opOpenSocialPlugin
 * @subpackage model
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class PluginApplicationTable extends Doctrine_Table
{
  const ADD_APPLICATION_DENY = 0;
  const ADD_APPLICATION_NECESSARY_TO_PERMIT = 1;
  const ADD_APPLICATION_ALLOW = 2;

 /**
  * get add application rule selection
  *
  * @return array
  */
  public function getAddApplicationRuleChoices()
  {
    $result = array(
      self::ADD_APPLICATION_DENY                => "Deny",
      self::ADD_APPLICATION_NECESSARY_TO_PERMIT => "The SNS administrator's permission is necessary",
      self::ADD_APPLICATION_ALLOW               => "Allow",
    );

    return array_map(array(sfContext::getInstance()->getI18N(), '__'), $result);
  }

  /**
   * add a new application
   *
   * @param string  $url
   * @param boolean $update
   * @param string  $culture
   * @return Application
   */
  public function addApplication($url, $update = false, $culture = null)
  {
    if ($culture === null)
    {
      $culture = sfContext::getInstance()->getUser()->getCulture();
    }

    if ($culture != sfConfig::get('sf_default_culture'))
    {
      self::addApplication($url, $update, sfConfig::get('sf_default_culture'));
    }

    $application = $this->findOneByUrl($url);

    if (!$application)
    {
      $application = new Application();
    }

    if (isset($application->Translation[$culture]) && !$update)
    {
      $ua = $application->getUpdatedAt();
      $time = strtotime($ua);
      if (!empty($ua) && (time() - $ua) <= Doctrine::getTable('SnsConfig')->get('application_cache_time', 24*60*60))
      {
        return $application;
      }
    }

    $gadget = opOpenSocialToolKit::fetchGadgetMetadata($url, $culture);

    $prefs = array();
    foreach ($gadget->gadgetSpec->userPrefs as $pref)
    {
      $prefs[$pref['name']] = $pref;
    }

    $views = array();
    foreach ($gadget->gadgetSpec->views as $name => $view)
    {
      unset($view['content']);
      $views[$name] = $view;
    }

    $translation = $application->Translation[$culture];
    $application->setUrl($url);
    $translation->title              = $gadget->getTitle();
    $translation->title_url          = $gadget->getTitleUrl();
    $translation->description        = $gadget->getDescription();
    $translation->directory_title    = $gadget->getDirectoryTitle();
    $translation->screenshot         = $gadget->getScreenShot();
    $translation->thumbnail          = $gadget->getThumbnail();
    $translation->author             = $gadget->getAuthor();
    $translation->author_aboutme     = $gadget->getAuthorAboutme();
    $translation->author_affiliation = $gadget->getAuthorAffiliation();
    $translation->author_email       = $gadget->getAuthorEmail();
    $translation->author_photo       = $gadget->getAuthorPhoto();
    $translation->author_link        = $gadget->getAuthorLink();
    $translation->author_quote       = $gadget->getAuthorQuote();
    $translation->settings           = $prefs;
    $translation->views              = $views;

    if ($gadget->getScrolling() == 'true')
    {
      $application->setScrolling(true);
    }
    else
    {
      $application->setScrolling(false);
    }

    $singleton = $gadget->getSingleton();
    if ($singleton == 'true' || empty($singleton))
    {
      $application->setSingleton(true);
    }
    else
    {
      $application->setSingleton(false);
    }

    $height = $gadget->getHeight();
    $application->setHeight(!empty($height) ? $height : 0);
    $application->save();
    return $application;
  }

  public function getApplicationListPager($page = 1, $size = 20, $memberId = null, $isActive = null, $orderBy = 'id desc')
  {
    $query = $this->createQuery();

    if (null !== $memberId)
    {
      $query->addWhere('member_id = ?', $memberId);
    }

    if (is_bool($isActive))
    {
      $query->addWhere('is_active = ?', $isActive);
    }

    if ($orderBy)
    {
      $query->orderBy($orderBy);
    }

    $pager = new sfDoctrinePager('Application', $size);
    $pager->setQuery($query);
    $pager->setPage($page);
    $pager->init();
    return $pager;
  }
}

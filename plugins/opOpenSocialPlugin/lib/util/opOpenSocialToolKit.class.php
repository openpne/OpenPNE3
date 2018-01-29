<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialToolKit
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class opOpenSocialToolKit
{
  static protected function arrayToObject($array)
  {
    foreach ($array as &$a)
    {
      if (is_array($a))
      {
        $a = self::arrayToObject($a);
      }
    }

    return (object)$array;
  }

  /**
   * fetch a OpenSocial application metadata
   *
   * @param string $url
   * @param string $culture
   */
  static public function fetchGadgetMetadata($url, $culture)
  {
    $cul = split('_', $culture);

    $_GET['nocache'] = 1;
    $context = new MetadataGadgetContext(self::arrayToObject(array(
      'country'   => isset($cul[1]) ? $cul[1] : 'ALL',
      'language'  => $cul[0],
      'view'      => 'default',
      'container' => 'openpne',
    )), $url);
    $gadgetServer = new GadgetFactory($context, null);
    $gadgets = $gadgetServer->createGadget();
    return $gadgets;
  }

 /**
  * Check enable home gadget
  *
  * @return boolean
  */
  static public function isEnableHomeGadget()
  {
    $homeGadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('gadget');
    foreach ($homeGadgets as $gadgets)
    {
      if ($gadgets)
      {
        foreach ($gadgets as $gadget)
        {
          if (($gadget instanceof Gadget) && $gadget->getName() == 'applicationBoxes')
          {
            return true;
          }
        }
      }
    }
    return false;
  }

 /**
  * Check enable profile gadget
  *
  * @return boolean
  */
  static public function isEnableProfileGadget()
  {
    $profileGadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('profile');
    foreach ($profileGadgets as $gadgets)
    {
      if ($gadgets)
      {
        foreach ($gadgets as $gadget)
        {
          if (($gadget instanceof Gadget) && $gadget->getName() == 'applicationBoxes')
          {
            return true;
          }
        }
      }
    }
    return false;
  }
}

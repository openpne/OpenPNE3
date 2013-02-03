<?php

/**
 * googlemaps actions.
 *
 * @package    OpenPNE
 * @subpackage googlemaps
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class googlemapsActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->mapType = 'google.maps.MapTypeId.ROADMAP';
    switch ($request->getParameter('t'))
    {
      case 'k':
        $this->mapType = 'google.maps.MapTypeId.SATELLITE';
        break;
      case 'h':
        $this->mapType = 'google.maps.MapTypeId.HYBRID';
        break;
    }
  }
}

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
    $this->mapType = 'G_NORMAL_MAP';
    switch ($request->getParameter('t'))
    {
      case 'k':
        $this->mapType = 'G_SATELLITE_MAP';
        break;
      case 'h':
        $this->mapType = 'G_HYBRID_MAP';
        break;
    }
  }
}

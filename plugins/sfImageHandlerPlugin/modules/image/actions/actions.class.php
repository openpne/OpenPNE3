<?php

/**
 * image actions.
 *
 * @package    sfImageHandlerPlugin
 * @subpackage image
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class imageActions extends sfActions
{
  public function preExecute()
  {
    sfConfig::set('sf_web_debug', false);
    $this->getUser()->undeleteFlash();
  }

 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfRequest $request)
  {
    $params = array(
      'filename' => $request->getParameter('filename', null),
      'format'   => $request->getParameter('format', null),
      'width'    => str_replace('w', '', $request->getParameter('width', null)),
      'height'   => str_replace('h', '', $request->getParameter('height', null)),
    );
    $image = new sfImageHandler($params);
    $this->forward404Unless($image->isValidSource(), 'Invalid URL.');

    $binary = $image->createImage();

    header('Content-Type:'.$image->getContentType());
    echo $binary;

    exit;
  }
}

<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opTimelineActions
 *
 * @package    OpenPNE
 * @subpackage opTimelinePlugin
 */

class opTimelineActions extends sfActions
{
  public function updateTimeline($request)
  {
    $newObject = new ActivityData();
    $newObject->setMemberId($this->getUser()->getMemberId());
    $this->form = new TimelineDataForm($newObject);
    $params = $request->getParameter('activity_data');
    $this->form->bind($params);
    if ($this->form->isValid())
    {
      $this->form->save();
      if ($request->isXmlHttpRequest())
      {
        $this->getContext()->getConfiguration()->loadHelpers('Partial');
        return $this->renderText(get_partial('default/activityRecord', array('activity' => $this->form->getObject())));
      }
      else
      {
        $this->redirect($params['next_uri']);
      }
    }
    else
    {
      if ($request->isXmlHttpRequest())
      {
        $this->getResponse()->setStatusCode(500);
      }
      else
      {
        $this->getUser()->setFlash('error', 'Failed to post %activity%.');
        if (isset($params['next_uri']))
        {
          $this->redirect($params['next_uri']);
        }
        $this->redirect('@homepage');
      }
    }
  }
}

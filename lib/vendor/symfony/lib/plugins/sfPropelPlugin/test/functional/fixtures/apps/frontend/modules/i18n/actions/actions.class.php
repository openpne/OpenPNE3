<?php

/**
 * i18n actions.
 *
 * @package    test
 * @subpackage i18n
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 16987 2009-04-04 14:16:46Z fabien $
 */
class i18nActions extends sfActions
{
  public function executeIndex()
  {
    $this->getUser()->setCulture('fr');

    $this->movies = MoviePeer::doSelect(new Criteria());
  }

  public function executeDefault()
  {
    $this->movies = MoviePeer::doSelect(new Criteria());

    $this->setTemplate('index');
  }

  public function executeMovie($request)
  {
    $this->form = new MovieForm(MoviePeer::retrieveByPk($request->getParameter('id')));

    if ($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($request->getParameter('movie'));

      if ($this->form->isValid())
      {
        $movie = $this->form->save();

        $this->redirect('i18n/movie?id='.$movie->getId());
      }
    }
  }
}

<?php

/**
 * word actions.
 *
 * @package    OpenPNE
 * @subpackage word
 * @author     Masato Nagasawa <nagasawa@tejimaya.net>
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class wordActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $wordConfigs = WordConfigPeer::doSelectWithI18n(new Criteria());
    $this->form = new WordConfigsForm(array(), array('word_configs' => $wordConfigs));

    if ($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($request->getParameter('word_configs'));
      $this->redirectIf($this->form->save(), 'word/index');
    }
  }
}

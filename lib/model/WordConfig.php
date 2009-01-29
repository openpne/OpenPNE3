<?php

class WordConfig extends BaseWordConfig
{
  public function getWordType()
  {
    $c = new Criteria();
    $c->add(WordTypePeer::ID, $this->word_type_id);
    $wordType = WordTypePeer::doSelectWithI18n($c);
    return $wordType[0]->getValue();
  }
}

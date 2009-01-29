<?php

class WordConfigPeer extends BaseWordConfigPeer
{
  public static function getWordName($name)
  {
    $c = new Criteria();
    $c->add(WordConfigPeer::NAME, $name);
    return WordConfigPeer::doSelectWithI18n($c)->getValue();
  }
}

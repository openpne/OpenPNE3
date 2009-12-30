<?php

class sfMessageSource_OpenPNE extends sfMessageSource_XLIFF
{
  public function &loadData($filename)
  {
    $result = parent::loadData($filename);

    // OpenPNE doesn't allow translating empty string
    unset($result['']);

    return $result;
  }
}

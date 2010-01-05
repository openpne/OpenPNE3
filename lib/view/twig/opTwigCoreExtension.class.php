<?php

class opTwigCoreExtension extends Twig_Extension_Core
{
  public function getFilters()
  {
    return array_merge(parent::getFilters(), array(
      'date' => new Twig_Filter_Method($this, 'dateFilter'),
    ));
  }

  public function dateFilter($timestamp, $format)
  {
    if (!ctype_digit($timestamp))
    {
      // it must be converted as timestamp
      $timestamp = strtotime($timestamp);
    }

    return twig_date_format_filter($timestamp, $format);
  }
}

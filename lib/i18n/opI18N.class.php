<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opI18N extends sfI18N
{
  protected
    $terms = null,
    $parsed = array();

  public $titleize = false;

  public function initialize(sfApplicationConfiguration $configuration, sfCache $cache = null, $options = array())
  {
    parent::initialize($configuration, $cache, $options);

    $this->terms = Doctrine::getTable('SnsTerm');
    $this->terms->configure($this->culture, sfConfig::get('sf_app'));
  }

  public function __($string, $args = array(), $catalogue = 'messages')
  {
    foreach ($args as $k => $v)
    {
      if ($v instanceof SnsTerm)
      {
        $args[$k] = (string)$v;
      }
    }

    if (empty($parsed[$string]))
    {
      $this->parsed[$string] = array();

      $matches = array();
      preg_match_all('/%([a-zA-Z_]+)%/', $string, $matches);

      array_shift($matches);

      foreach ($matches as $match)
      {
        foreach ($match as $v)
        {
          if ($this->terms[$v])
          {
            $term = $this->terms[$v];
            if ($this->titleize)
            {
              $term = $term->titleize();
            }

            $this->parsed[$string]['%'.$v.'%'] = (string)$term;
          }
        }
      }
    }

    return parent::__($string, array_merge($this->parsed[$string], $args), $catalogue);
  }
}

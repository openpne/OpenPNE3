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
    $application = sfConfig::get('sf_app');
    if ('pc_backend' == $application)
    {
        $application = 'pc_frontend';
    }
    $this->terms = Doctrine::getTable('SnsTerm');
    $this->terms->configure($this->culture, $application);
    if (!$this->terms['member'])
    {
      $this->terms->configure('en', $application);
    }
  }

  public function generateApplicationMessages($dirs)
  {
    $catalogues = array();

    $files = sfFinder::type('file')
      ->follow_link()
      ->name('*.xml')
      ->maxdepth(1)
      ->in($dirs);

    foreach ($files as $file)
    {
      $name = basename($file);
      if (empty($catalogues[$name]))
      {
        $catalogues[$name] = array();
      }

      $messageSource = sfMessageSource::factory('OpenPNE', array());
      $data = $messageSource->loadData($file);

      $catalogues[$name] = array_merge($data, $catalogues[$name]);
    }

    $cacheDir = sfConfig::get('sf_app_cache_dir').DIRECTORY_SEPARATOR.'i18n';

    foreach ($catalogues as $filename => $catalogue)
    {
      $path = $cacheDir.DIRECTORY_SEPARATOR.$filename.'.php';
      opToolkit::writeCacheFile($path, '<?php return '.var_export($catalogue, true).';');
    }
  }

  public function setMessageSource($dirs, $culture = null)
  {
    $cachedDir = sfConfig::get('sf_app_cache_dir').DIRECTORY_SEPARATOR.'i18n';
    if (is_file($cachedDir.DIRECTORY_SEPARATOR.'messages.ja.xml.php'))
    {
      $this->messageSource = sfMessageSource::factory('OpenPNECached', $cachedDir);
    }
    else
    {
      $this->generateApplicationMessages($dirs);

      if (!is_null($dirs))
      {
        $this->messageSource = $this->createMessageSource();
      }
      else
      {
        $this->messageSource = sfMessageSource::factory('Aggregate', array_map(array($this, 'createMessageSource'), $dirs));
      }
    }

    if (!is_null($this->cache))
    {
      $this->messageSource->setCache($this->cache);
    }

    if (!is_null($culture))
    {
      $this->setCulture($culture);
    }
    else
    {
      $this->messageSource->setCulture($this->culture);
    }

    $this->messageFormat = null;
  }


  public function __($string, $args = array(), $catalogue = 'messages')
  {
    if (empty($this->parsed[$string]))
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

    $parsedString = $this->parsed[$string];
    if (is_array($args))
    {
      foreach ($args as $k => $v)
      {
        if ($v instanceof SnsTerm)
        {
          $args[$k] = (string)$v;
        }
      }
      $parsedString = array_merge($parsedString, $args);
    }

    return parent::__($string, $parsedString, $catalogue);
  }
}

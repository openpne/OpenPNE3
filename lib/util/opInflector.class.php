<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The opInflector transforms words from singular to plural, and more.
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opInflector extends sfInflector
{
  /**
   * From CakePHP 1.2.5
   *
   * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
   * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
   *
   * Licensed under The MIT License
   */
  public static $pluralRules = array(
    'pluralRules' => array(
      '/(s)tatus$/i' => '\1\2tatuses',
      '/(quiz)$/i' => '\1zes',
      '/^(ox)$/i' => '\1\2en',
      '/([m|l])ouse$/i' => '\1ice',
      '/(matr|vert|ind)(ix|ex)$/i'  => '\1ices',
      '/(x|ch|ss|sh)$/i' => '\1es',
      '/([^aeiouy]|qu)y$/i' => '\1ies',
      '/(hive)$/i' => '\1s',
      '/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
      '/sis$/i' => 'ses',
      '/([ti])um$/i' => '\1a',
      '/(p)erson$/i' => '\1eople',
      '/(m)an$/i' => '\1en',
      '/(c)hild$/i' => '\1hildren',
      '/(buffal|tomat)o$/i' => '\1\2oes',
      '/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|vir)us$/i' => '\1i',
      '/us$/' => 'uses',
      '/(alias)$/i' => '\1es',
      '/(ax|cris|test)is$/i' => '\1es',
      '/s$/' => 's',
      '/^$/' => '',
      '/$/' => 's',
    ),

    'uninflected' => array(
       '.*[nrlm]ese', '.*deer', '.*fish', '.*measles', '.*ois', '.*pox', '.*sheep', 'Amoyese',
       'bison', 'Borghese', 'bream', 'breeches', 'britches', 'buffalo', 'cantus', 'carp', 'chassis', 'clippers',
       'cod', 'coitus', 'Congoese', 'contretemps', 'corps', 'debris', 'diabetes', 'djinn', 'eland', 'elk',
       'equipment', 'Faroese', 'flounder', 'Foochowese', 'gallows', 'Genevese', 'Genoese', 'Gilbertese', 'graffiti',
       'headquarters', 'herpes', 'hijinks', 'Hottentotese', 'information', 'innings', 'jackanapes', 'Kiplingese',
       'Kongoese', 'Lucchese', 'mackerel', 'Maltese', 'media', 'mews', 'moose', 'mumps', 'Nankingese', 'news',
       'nexus', 'Niasese', 'Pekingese', 'People', 'Piedmontese', 'pincers', 'Pistoiese', 'pliers', 'Portuguese', 'proceedings',
       'rabies', 'rice', 'rhinoceros', 'salmon', 'Sarawakese', 'scissors', 'sea[- ]bass', 'series', 'Shavese', 'shears',
       'siemens', 'species', 'swine', 'testes', 'trousers', 'trout', 'tuna', 'Vermontese', 'Wenchowese',
       'whiting', 'wildebeest', 'Yengeese',
    ),

    'irregular' => array(
       'atlas' => 'atlases',
       'beef' => 'beefs',
       'brother' => 'brothers',
       'child' => 'children',
       'corpus' => 'corpuses',
       'cow' => 'cows',
       'ganglion' => 'ganglions',
       'genie' => 'genies',
       'genus' => 'genera',
       'graffito' => 'graffiti',
       'hoof' => 'hoofs',
       'loaf' => 'loaves',
       'man' => 'men',
       'money' => 'monies',
       'mongoose' => 'mongooses',
       'move' => 'moves',
       'mythos' => 'mythoi',
       'numen' => 'numina',
       'occiput' => 'occiputs',
       'octopus' => 'octopuses',
       'opus' => 'opuses',
       'ox' => 'oxen',
       'penis' => 'penises',
       'person' => 'people',
       'sex' => 'sexes',
       'soliloquy' => 'soliloquies',
       'testis' => 'testes',
       'trilby' => 'trilbys',
       'turf' => 'turfs',
    ),
  );

  public static $pluralized = array();

  /**
   * From CakePHP 1.2.5
   *
   * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
   * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
   *
   * Licensed under The MIT License
   */
  public static function pluralize($word)
  {
    if (isset(self::$pluralized[$word]))
    {
      return self::$pluralized[$word];
    }

    extract(self::$pluralRules);

    if (!isset($regexUninflected) || !isset($regexIrregular))
    {
      $regexUninflected = self::encloseForPregMatching(join('|', $uninflected));
      $regexIrregular = self::encloseForPregMatching(join('|', array_keys($irregular)));
      self::$pluralRules['regexUninflected'] = $regexUninflected;
      self::$pluralRules['regexIrregular'] = $regexIrregular;
    }

    if (preg_match('/^('.$regexUninflected.')$/i', $word))
    {
      self::$pluralized[$word] = $word;

      return $word;
    }

    $regs = array();
    if (preg_match('/(.*)\\b('.$regexIrregular.')$/i', $word, $regs))
    {
      self::$pluralized[$word] = $regs[1].substr($word, 0, 1).substr($irregular[strtolower($regs[2])], 1);

      return self::$pluralized[$word];
    }

    foreach ($pluralRules as $rule => $replacement)
    {
      if (preg_match($rule, $word))
      {
        self::$pluralized[$word] = preg_replace($rule, $replacement, $word);

        return self::$pluralized[$word];
      }
    }
  }

  /**
   * From CakePHP 1.2.5
   *
   * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
   * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
   *
   * Licensed under The MIT License
   */
  protected static function encloseForPregMatching($string)
  {
    return '(?:'.$string.')';
  }

  public static function getArticle($word)
  {
    if (in_array($word[0], array('a', 'e', 'i', 'o', 'u')))
    {
      return 'an';
    }

    return 'a';
  }
}

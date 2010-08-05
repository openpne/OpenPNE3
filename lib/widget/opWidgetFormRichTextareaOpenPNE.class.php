<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opWidgetFormRichTextareaOpenPNE
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Shogo Kawahara <kawahara@ejimaya.net>
 */
class opWidgetFormRichTextareaOpenPNE extends opWidgetFormRichTextarea
{
  static protected $isFirstRenderOpenPNE  = true;
  static protected $isConfiguredTinyMCE   = false;

  static protected $plugins = array('openpne');

  static protected $buttons = array(
    'op_b' => array('caption' => 'Bold'),
    'op_u' => array('caption' => 'Underline'),
    'op_s' => array('caption' => 'Strikethrough'),
    'op_i' => array('caption' => 'Itaric'),
    'op_large' => array('caption' => 'Large'),
    'op_small' => array('caption' => 'Small'),
    'op_color' => array('caption' => 'Select text color'),
    'op_emoji_docomo' => array('caption' => 'Input Emoji(DoCoMo)')
  );

  static protected $useButtons = null;

  static protected $buttonOnclickActions = array(
    'op_emoji_docomo' => 'opEmoji.getInstance("%id%").togglePallet("epDocomo");',
    'op_large' => 'op_mce_insert_tagname("%id%", "op:font", \' size="5"\');',
    'op_small' => 'op_mce_insert_tagname("%id%", "op:font", \' size="1"\');',
    'op_color' => 'op_mce_show_color_table("%id%", "op:font");'
  );

  static protected $convertCallbackList = array(
    'op:color' => array(__CLASS__, 'opColorToHtml'),
    'op:font'  => array(__CLASS__, 'opFontToHtml')
  );

  static protected $htmlConvertList = array(
    'op:b' => array('b'),
    'op:u' => array('u'),
    'op:i' => array('i'),
    'op:s' => array('s'),
    'op:large' => array('font', array('size' => 5)),
    'op:small' => array('font', array('size' => 1)),
  );

  static protected $extensions = array();

  protected $tinyMCEConfigs = array(
    'mode'                            => 'textareas',
    'theme'                           => 'advanced',
    'editor_selector'                 => 'mceEditor_dummy_selector',
    'plugins'                         => '',
    'theme_advanced_toolbar_location' => 'top',
    'theme_advanced_toolbar_align'    => 'left',
    'theme_advanced_buttons1'         => '',
    'theme_advanced_buttons2'         => '',
    'theme_advanced_buttons3'         => '',
    'valid_elements'                  => 'b/strong,u,s/strike,i/em,font[color|size],span[style],br',
    'forced_root_block'               => false,
    'force_p_newlines'                => false,
    'force_br_newlines'               => true,
    'inline_styles'                   => false,
    'entity_encoding'                 => 'raw',
    'remove_linebreaks'               => false,
    'custom_undo_redo_levels'         => 0,
    'custom_undo_redo'                => false,
    'convert_fonts_to_spans'          => true,
  );

  protected $loadPluginList = array();

  static public function addExtension($extension)
  {
    self::$extensions[] = $extension;
  }

  static public function getAllButtons()
  {
    $buttons = array();
    foreach (self::$buttons as $key => $button)
    {
      if (is_numeric($key))
      {
        $buttonName = $button;
        $buttonConfig = array('imageURL' => image_path('deco_'.$buttonName.'.gif'));
      }
      else
      {
        $buttonName = $key;
        if (!isset($button['imageURL']))
        {
          $button['imageURL'] = image_path('deco_'.$buttonName.'.gif');
        }
        $buttonConfig = $button;
      }
      $buttons[$buttonName] = $buttonConfig;
    }

    return $buttons;
  }

  static public function getButtons()
  {
    if (null !== self::$useButtons)
    {
      return self::$useButtons;
    }

    $buttons = self::getAllButtons();
    $unenableButtons = Doctrine::getTable('SnsConfig')->get('richtextarea_unenable_buttons', null);
    $unenableButtons = unserialize($unenableButtons);

    if (is_array($unenableButtons) && count($unenableButtons))
    {
      foreach ($unenableButtons as $buttonName)
      {
        if (isset($buttons[$buttonName]))
        {
          unset($buttons[$buttonName]);
        }
      }
    }

    $buttonsSortOrder = Doctrine::getTable('SnsConfig')->get('richtextarea_buttons_sort_order', null);
    $buttonsSortOrder = unserialize($buttonsSortOrder);

    if (is_array($buttonsSortOrder) && count($buttonsSortOrder))
    {
      $newButtons = array();
      foreach ($buttonsSortOrder as $buttonName)
      {
        if (isset($buttons[$buttonName]))
        {
          $newButtons[$buttonName] = $buttons[$buttonName];
          unset($buttons[$buttonName]);
        }
      }
      self::$useButtons = $newButtons + $buttons;
    }
    else
    {
      self::$useButtons = $buttons;
    }
    return self::$useButtons;
  }

  public function __construct($options = array(), $attributes = array())
  {
    sfProjectConfiguration::getActive()->loadHelpers('Asset');
    parent::__construct($options, $attributes);

    if (!isset($options['is_textmode']))
    {
      if (Doctrine::getTable('SnsConfig')->get('richtextarea_default_mode', 'text') === 'preview')
      {
        $this->setOption('is_textmode', false);
      }
    }

    foreach (self::$extensions as $extension)
    {
      if (!self::$isConfiguredTinyMCE)
      {
        self::$plugins              = array_merge(self::$plugins, call_user_func(array($extension, 'getPlugins')));
        self::$buttons              = array_merge_recursive(self::$buttons, call_user_func(array($extension, 'getButtons')));
        self::$buttonOnclickActions = array_merge(self::$buttonOnclickActions, call_user_func(array($extension, 'getButtonOnClickActions')));
        self::$convertCallbackList  = array_merge(self::$convertCallbackList, call_user_func(array($extension, 'getConvertCallbacks')));
        self::$htmlConvertList      = array_merge(self::$htmlConvertList, call_user_func(array($extension, 'getHtmlConverts')));
      }
    }

    if (!empty($this->tinyMCEConfigs['plugins']))
    {
      $this->tinyMCEConfigs['plugins'] .= ',';
    }
    $plugins = array();
    foreach (self::$plugins as $name => $path)
    {
      if (is_numeric($name))
      {
        $plugins[] = $path;
      }
      else
      {
        $plugins[] = '-'.$name;
        $this->loadPluginList[$name] = $path;
      }
    }
    $this->tinyMCEConfigs['plugins'] .= implode(',', $plugins);

    if (!empty($this->tinyMCEConfigs['theme_advanced_buttons1']))
    {
      $this->tinyMCEConfigs['theme_advanced_buttons1'] .= ',';
    }
    $this->tinyMCEConfigs['theme_advanced_buttons1'] .= implode(',', array_keys(self::getButtons()));

    foreach (self::$extensions as $extension)
    {
      call_user_func_array(array($extension, 'configure'), array(&$this->tinyMCEConfigs));
    }

    self::$isConfiguredTinyMCE = true;
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (sfConfig::get('sf_app') == 'mobile_frontend')
    {
      return parent::render($name, $value, $attributes, $errors);
    }

    $js = '';

    if (self::$isFirstRenderOpenPNE)
    {
      sfProjectConfiguration::getActive()->loadHelpers('Partial');
      sfContext::getInstance()->getResponse()->addJavascript('/sfProtoculousPlugin/js/prototype');
      sfContext::getInstance()->getResponse()->addJavascript('op_emoji');
      sfContext::getInstance()->getResponse()->addJavascript('Selection');
      sfContext::getInstance()->getResponse()->addJavascript('decoration');

      $relativeUrlRoot = sfContext::getInstance()->getRequest()->getRelativeUrlRoot();

      foreach ($this->loadPluginList as $key => $path)
      {
        $js .= sprintf('tinymce.PluginManager.load("%s", "%s");'."\n", $key, $path);
      }

      $js .= sprintf("function op_mce_editor_get_config() { return %s; }\n", json_encode(self::getButtons()));
      $js .= sprintf('function op_get_relative_uri_root() { return "%s"; }', $relativeUrlRoot);

      self::$isFirstRenderOpenPNE = false;
    }

    if ($js)
    {
      sfProjectConfiguration::getActive()->loadHelpers('Javascript');
      $js = javascript_tag($js);
    }

    $id = $this->getId($name, $attributes);
    $this->setOption('textarea_template', '<div id="'.$id.'_buttonmenu" class="'.$id.'">'
      .get_partial('global/richTextareaOpenPNEButton', array(
        'id' => $id,
        'configs' => self::getButtons(),
        'onclick_actions' => self::$buttonOnclickActions
      )).
      '</div>'.$this->getOption('textarea_template'));

    return $js.parent::render($name, $value, $attributes, $errors);
  }

 /**
  * original tag to html
  *
  * @param string  $string
  * @param boolean $isStrip          true if original tag is stripped from the string, false original tag convert html tag. 
  * @param boolean $isUseStylesheet
  */
  static public function toHtml($string, $isStrip, $isUseStylesheet)
  {
    new self();
    $regexp = '/(?:&lt;|<)(\/?)(op:.+?)(?:\s+(.*?))?(?:&gt;|>)/i';

    if ($isStrip)
    {
      $converted = preg_replace($regexp, '', $string);
    }
    else
    {
      if ($isUseStylesheet)
      {
        $converted = preg_replace_callback($regexp, array(__CLASS__, 'toHtmlUseStylesheet'), $string);
      }
      else
      {
        $converted = preg_replace_callback($regexp, array(__CLASS__, 'toHtmlNoStylesheet'), $string);
      }
    }

    return $converted;
  }

  static protected function getHtmlAttribute($matches)
  {
    $result = array();
    if (count($matches) <= 3)
    {
      return $result;
    }
    preg_match_all('/([^\s]*?)=(?:&quot;|")(.*?)(?:&quot;|")/', $matches[3], $matchAttributes);
    for ($i = 0; count($matchAttributes[0]) > $i; $i++)
    {
      $result[$matchAttributes[1][$i]] = $matchAttributes[2][$i];
    }
    return $result;
  }

  static public function toHtmlUseStylesheet($matches)
  {
    $isEndtag = $matches[1];
    $tagname = strtolower($matches[2]);
    $attributes = self::getHtmlAttribute($matches);
    if (isset(self::$convertCallbackList[$tagname]))
    {
      return call_user_func(self::$convertCallbackList[$tagname], $isEndtag, $tagname, $attributes, true);
    }

    $options = array();
    $options['class'] = strtr($tagname, ':', '_');
    if ($isEndtag) {
      return '</span>';
    }

    return tag('span', $options, true);
  }

  static public function toHtmlNoStylesheet($matches)
  {
    $isEndtag = $matches[1];
    $tagname = strtolower($matches[2]);
    $attributes = self::getHtmlAttribute($matches);
    if (isset(self::$convertCallbackList[$tagname]))
    {
      return call_user_func(self::$convertCallbackList[$tagname], $isEndtag, $tagname, $attributes, false);
    }

    $options = array();
    if (!array_key_exists($tagname, self::$htmlConvertList)) {
      return '';
    }

    $htmlTagInfo = self::$htmlConvertList[$tagname];
    $htmlTagName = $htmlTagInfo[0];

    if ($isEndtag) {
      return '</' . $htmlTagName . '>';
    }

    if (isset($htmlTagInfo[1]) && is_array($htmlTagInfo[1]))
    {
      $options = array_merge($options, $htmlTagInfo[1]);
    }

    return tag($htmlTagName, $options, true);
  }

  static public function opColorToHtml($isEndtag, $tagname, $attributes, $isUseStylesheet)
  {
    $options = array();
    if ($isUseStylesheet)
    {
      if ($isEndtag) {
        return '</span>';
      }
      $options['class'] = strtr($tagname, ':', '_');
      if (isset($attributes['code'])) {
        $options['style'] = 'color:'.$attributes['code'];
      }

      return tag('span', $options, true);
    }
    else
    {
      if ($isEndtag)
      {
        return '</font>';
      }
      if (isset($attributes['code'])) {
        $options['color'] = $attributes['code'];
      }

      return tag('font', $options, true);
    }
  }

  static public function opFontToHtml($isEndtag, $tagname, $attributes, $isUseStylesheet)
  {
    $options = array();
    if ($isUseStylesheet)
    {
      if ($isEndtag) {
        return '</span>';
      }
      $options['class'] = 'op_font';
      $options['style'] = '';
      if (isset($attributes['color'])) {
        $options['style'] .= 'color:'.$attributes['color'].';';
      }
      $size = isset($attributes['size']) ? (int)$attributes['size'] : 0;
      $fontSizeMap = array(
        1 => 'xx-small',
        2 => 'x-small',
        3 => 'small',
        4 => 'medium',
        5 => 'large',
        6 => 'x-large',
        7 => 'xx-large'
      );
      if (isset($fontSizeMap[$size])) {

        $options['style'] .= 'font-size:'.$fontSizeMap[$size];
      }

      return tag('span', $options, true);
    }
    else
    {
      if ($isEndtag)
      {
        return '</font>';
      }
      if (isset($attributes['color'])) {
        $options['color'] = $attributes['color'];
      }
      $size = isset($attributes['size']) ? (int)$attributes['size'] : 0;
      if ($size >= 1 && $size <= 7)
      {
        $options['size'] = $attributes['size'];
      }

      return tag('font', $options, true);
    }
  }
}

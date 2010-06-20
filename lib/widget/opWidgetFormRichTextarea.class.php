<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opWidgetFormRichTextarea 
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Shogo Kawahara <kawahara@ejimaya.net>
 */
class opWidgetFormRichTextarea extends sfWidgetFormTextarea
{
  static protected $isFirstRender = true;

  protected $tinyMCEConfigs = array(
    'mode'  => 'textareas',
    'theme' => 'advanced',
    'editor_selector' => 'mceEditor_dummy_selector',
    'theme_advanced_toolbar_location' => 'top',
    'theme_advanced_toolbar_align' => 'left',
    'theme_advanced_buttons1' => 'bold, italic, undefined, forecolor, hr',
    'theme_advanced_buttons2' => '',
    'theme_advanced_buttons3' => '',
  );

  public function __construct($options = array(), $attributes = array())
  {
    parent::__construct($options, $attributes);

    $this->tinyMCEConfigs = array_merge($this->tinyMCEConfigs, $this->getOption('config'));

    if (!isset($this->tinyMCEConfigs['language']) && sfContext::hasInstance())
    {
      $lang = explode('_', sfContext::getInstance()->getUser()->getCulture());
      $this->tinyMCEConfigs['language'] = $lang[0];
    }
  }

  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('config', array());
    $this->addOption('is_toggle', true);
    $this->addOption('is_textmode', true);
    $this->addOption('textarea_template', '%s');

    parent::configure($options, $attributes);
  }

  protected function getId($name, $attributes)
  {
    if (isset($attributes['id']))
    {
      return $attributes['id'];
    }
    $tmpAttributes = $this->fixFormId(array_merge(array('name' => $name, $attributes)));
    return $tmpAttributes['id'];
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (sfConfig::get('sf_app') == 'mobile_frontend')
    {
      return parent::render($name, $value, $attributes, $errors);
    }

    $toggle = '';
    $js = '';

    $id = $this->getId($name, $attributes);

    $changerName = $id.'_changer';
    $offId = $id.'_changer_1';
    $onId  = $id.'_changer_2';
    if (self::$isFirstRender)
    {
      sfContext::getInstance()->getResponse()->addJavascript('/sfProtoculousPlugin/js/prototype');
      sfContext::getInstance()->getResponse()->addJavascript('tiny_mce/tiny_mce');
      $js .= <<<EOF
  function op_toggle_mce_editor(id)
  {
    var textmode_checked    = $(id + "_changer_1").checked;
    var previewmode_checked = $(id + "_changer_2").checked;
    var relational_objects  = $$("." + id);
    var editor = tinyMCE.get(id);
    if (!editor) {
      if (previewmode_checked) {
        tinyMCE.execCommand('mceAddControl', false, id);
        relational_objects.each(function(object){ object.style.display = "none"; });
      }
      return true;
    }
    if (editor.isHidden() && previewmode_checked) {
      editor.show();
      relational_objects.each(function(object){ object.style.display = "none"; });
    } else if (!editor.isHidden() && textmode_checked) {
      editor.hide();
      relational_objects.each(function(object){ object.style.display = "block"; });
    }
  }

EOF;

      $js .= sprintf("  tinyMCE.init(%s);\n", json_encode($this->tinyMCEConfigs));

      self::$isFirstRender = false;
    }

    $js .= sprintf("  op_toggle_mce_editor('%s');\n", $id);

    if ($js)
    {
      sfProjectConfiguration::getActive()->loadHelpers('Javascript');
      $js = javascript_tag($js);
    }

    if ($this->getOption('is_toggle'))
    {
      $toggle = sprintf(<<<EOF
<input id="%s" type="radio" name="%s" onclick="%s"%s /><label for="%s">%s</label>
<input id="%s" type="radio" name="%s" onclick="%s"%s /><label for="%s">%s</label><br />
EOF
      ,
        $offId,
        $changerName,
        sprintf("op_toggle_mce_editor('%s')", $id),
        $this->getOption('is_textmode') ? ' checked="checked"' : '',
        $offId,
        sfContext::getInstance()->getI18N()->__('Text Mode'),
        $onId,
        $changerName,
        sprintf("op_toggle_mce_editor('%s')", $id),
        $this->getOption('is_textmode') ? '' : ' checked="checked"',
        $onId,
        sfContext::getInstance()->getI18N()->__('Preview Mode')
      );
    }
    return $toggle.sprintf($this->getOption('textarea_template'), parent::render($name, $value, $attributes, $errors)).$js;
  }
}


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
  static protected $firstRender = true;

  static protected $defaultTinyMCEConfigs = array(
    'mode'  => 'textareas',
    'theme' => 'advanced',
    'editor_selector' => 'mceEditor_dummy_selector',
    'theme_advanced_toolbar_location' => 'top',
    'theme_advanced_toolbar_align' => 'left',
    'theme_advanced_buttons1' => 'bold, italic, undefined, forecolor, hr',
    'theme_advanced_buttons2' => '',
    'theme_advanced_buttons3' => '',
  );

  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('config', array());
    $this->addOption('is_toggle', true);
    $this->addOption('is_textmode', true);

    parent::configure($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $this->setOption('config', array_merge(self::$defaultTinyMCEConfigs, $this->getOption('config')));

    $toggle = '';
    $js = '';

    if (isset($attributes['id']))
    {
      $id = $attributes['id'];
    }
    else
    {
      $tmpAttributes = $this->fixFormId(array_merge(array('name' => $name, $attributes)));
      $id = $tmpAttributes['id'];
    }

    $changerName = $id.'_changer';
    $offId = $id.'_changer_1';
    $onId  = $id.'_changer_2';
    if (self::$firstRender)
    {
      sfContext::getInstance()->getResponse()->addJavascript('tiny_mce/tiny_mce');
      $js .= <<<EOF
  function op_toggle_mce_editor(id)
  {
    var textmode_checked = document.getElementById(id + "_changer_1").checked;
    var previewmode_checked = document.getElementById(id + "_changer_2").checked;
    var editor = tinyMCE.get(id);
    if (!editor) {
      if (previewmode_checked) 
        tinyMCE.execCommand('mceAddControl', 0, id);
      return true;
    }
    if (editor.isHidden() && previewmode_checked)
      editor.show();
    else if (!editor.isHidden() && textmode_checked)
      editor.hide();
  }

EOF;
      self::$firstRender = false;
    }

    $js .= sprintf("  tinyMCE.init(%s);\n", json_encode($this->getOption('config')));
    if (!$this->getOption('is_textmode'))
    {
      $js .= sprintf("  tinyMCE.execCommand('mceAddControl', false, '%s');\n", $id);
    }
    $js = '<script type="text/javascript">'."\n".$js.'</script>';
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
        $this->getOption('is_textmode') ? ' checked="true"' : '',
        $offId,
        sfContext::getInstance()->getI18N()->__('Text Mode'),
        $onId,
        $changerName,
        sprintf("op_toggle_mce_editor('%s')", $id),
        $this->getOption('is_textmode') ? '' : ' checked="true"',
        $onId,
        sfContext::getInstance()->getI18N()->__('Preview Mode')
      );
    }
    return $toggle.parent::render($name, $value, $attributes, $errors).$js;
  }
}


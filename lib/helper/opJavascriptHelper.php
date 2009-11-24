<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opJavascriptHelper.
 *
 * @package    OpenPNE
 * @subpackage helper
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */

require_once sfConfig::get('sf_plugins_dir').'/sfProtoculousPlugin/lib/helper/JavascriptHelper.php';

/**
 * Makes contents to be a modal box.
 *
 * @param string $contents
 */
function make_modal_box($id, $contents)
{
  sfContext::getInstance()->getResponse()->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/prototype');
  sfContext::getInstance()->getResponse()->addJavascript('util');
  $div = '<div id="'.$id.'" class="modalWall" style="display:none" onclick="this.style.display=\'none\'; $(\''.$id.'_contents\').style.display=\'none\'"></div>'
       . '<div id="'.$id.'_contents" class="modalBox" style="display: none;">'
       . $contents
       . '</div>'
       . '<script type="text/javascript">'
       . 'var contents = $("'.$id.'_contents");'
       . 'contents.setStyle(getCenterMuchScreen(contents))'
       . '</script>';

  return $div;
}

function link_to_modal_box($text, $id, $contents)
{
  $link = link_to_function($text, visual_effect('appear', $id, array('to' => '0.7')).';'.visual_effect('appear', $id.'_contents'))
        . make_modal_box($id, $contents);

  return $link;
}

function link_to_iframe_modal_box($text, $url_for, $id, $width, $height)
{
  $iframe = '<iframe id="'.$id.'_iframe" src="'.$url_for.'" width="'.$width.'px" height="'.$height.'px"></iframe>';
  return link_to_modal_box($text, $id, $iframe);
}


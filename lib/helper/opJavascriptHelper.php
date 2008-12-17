<?php

/**
 * opJavascriptHelper.
 *
 * @package    OpenPNE
 * @subpackage helper
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */

require_once sfConfig::get('sf_symfony_lib_dir').'/plugins/sfProtoculousPlugin/lib/helper/JavascriptHelper.php';

/**
 * Makes contents to be a modal box.
 *
 * @param string $contents
 */
function make_modal_box($id, $contents, $width, $height)
{
  $div = '<div id="'.$id.'" class="modalWall" style="display:none" onclick="this.style.display=\'none\'; document.getElementById(\''.$id.'_contents\').style.display=\'none\'"></div>'
       . '<div id="'.$id.'_contents" class="modalBox" style="display: none;">'
       . $contents
       . '</div>'
       . '<script type="text/javascript">'
       . 'var _pos = getCenterMuchScreen('.$width.','.$height.');'
       . 'document.getElementById("'.$id.'_contents").style.left = _pos.left + "px";'
       . 'document.getElementById("'.$id.'_contents").style.top = _pos.top + "px";'
       . '</script>';

  return $div;
}

function link_to_modal_box($text, $id, $contents, $width, $height)
{
  $link = link_to_function($text, visual_effect('appear', $id, array('to' => '0.7')).';'.visual_effect('appear', $id.'_contents'))
        . make_modal_box($id, $contents, $width, $height);

  return $link;
}

function link_to_iframe_modal_box($text, $url_for, $id, $width, $height)
{
  $iframe = '<iframe id="'.$id.'_iframe" src="'.$url_for.'" width="'.$width.'px" height="'.$height.'px"></iframe>';
  return link_to_modal_box($text, $id, $iframe, $width, $height);
}


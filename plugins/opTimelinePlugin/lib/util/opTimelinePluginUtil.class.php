<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opTimelinePluginUtil
 *
 * @package    OpenPNE
 * @subpackage opTimelinePlugin
 */

class opTimelinePluginUtil
{

  const DB_MAX_FILE_SIZE = 4294967295;

  public static function hasScreenName($body)
  {
    preg_match_all('/(@+)([-._0-9A-Za-z]+)/', $body, $matches);
    if ($matches[2])
    {
      $memberIds = array();
      foreach ($matches[2] as $screenName)
      {
        $member = Doctrine::getTable('MemberConfig')->findOneByNameAndValue('op_screen_name', $screenName);
        if ($member)
        {
          $memberIds[] = $member->getMemberId();
          $memberObject = Doctrine::getTable('Member')->find($member->getMemberId());
          opNotificationCenter::notify(sfContext::getInstance()->getUser()->getMember(), $memberObject, $body, array('category' => 'other', 'url' => url_for('@member_timeline?id='.sfContext::getInstance()->getUser()->getMemberId())));
        }
      }
      $memberId = implode("|", $memberIds);
      return '|'.$memberId.'|';
    }
    else
    {
      return null;
    }
  }

  public static function getFileSizeMax()
  {
    return min(
            (int) opTimelineDb::findVariableOfMySQL('max_allowed_packet'),
            self::calcConfigSizeToByte(ini_get('post_max_size')),
            self::calcConfigSizeToByte(ini_get('upload_max_filesize')),
            self::DB_MAX_FILE_SIZE);
  }

  const ONE_KB = 1024;

  public static function getFileSizeMaxOfFormat()
  {
    $bytes = self::getFileSizeMax();

    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

    $exp = floor(log($bytes) / log(self::ONE_KB));
    $unit = $units[$exp];
    $bytes = $bytes / pow(self::ONE_KB, floor($exp));
    return $bytes.$unit;
  }

  private static function calcConfigSizeToByte($v)
  {
    $l = substr($v, -1);
    $ret = substr($v, 0, -1);
    switch (strtoupper($l))
    {
      case 'P':
        $ret *= 1024;
      case 'T':
        $ret *= 1024;
      case 'G':
        $ret *= 1024;
      case 'M':
        $ret *= 1024;
      case 'K':
        $ret *= 1024;
        break;
    }
    return $ret;
  }

  public static function getUploadAllowImageTypeList()
  {
    return array(
        'jpeg',
        'pjpeg', //IEだとjpegがpjpegになる
        'gif',
        'png',
        'x-png', //IEだとpngがx-pngになる
    );
  }

}

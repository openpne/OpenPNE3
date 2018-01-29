<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMessageHelper.
 *
 * @package    OpenPNE
 * @subpackage helper
 * @author     Maki TAKAHASHI <takahashi@tejimaya.com>
 */

function op_message_link_to_member(sfOutputEscaper $member)
{
  if (function_exists('op_link_to_member'))
  {
    return op_link_to_member($member);
  }

  if ($member && $member->id)
  {
    if (sfConfig::get('sf_app') == 'mobile_frontend')
    {
      $internal_uri = '@member_profile';
    }
    else
    {
      $internal_uri = '@obj_member_profile';
    }
    return link_to($member->name, sprintf('%s?id=%d', $internal_uri, $member->id));
  }

  return '';
}

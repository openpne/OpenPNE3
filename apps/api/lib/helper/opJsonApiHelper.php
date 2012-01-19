<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opJsonApiHelper
 *
 * @package    OpenPNE
 * @subpackage helper
 * @author     Kimura Youichi <kim.upsilon@gmail.com>
 */
function op_api_member($member)
{
  $viewMemberId = sfContext::getInstance()->getUser()->getMemberId();

  $memberImageFileName = $member->getImageFileName();
  if (!$memberImageFileName)
  {
    $memberImage = op_image_path('no_image.gif', true);
  }
  else
  {
    $memberImage = sf_image_path($memberImageFileName, array('size' => '48x48'), true);
  }

  $relation = null;
  if ((string)$viewMemberId !== (string)$member->getId())
  {
    $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($viewMemberId, $member->getId());
  }

  $selfIntroduction = $member->getProfile('op_preset_self_introduction', true);

  return array(
    'id' => $member->getId(),
    'profile_image' => $memberImage,
    'screen_name' => $member->getConfig('op_screen_name', $member->getName()),
    'name' => $member->getName(),
    'profile_url' => op_api_member_profile_url($member->getId()),
    'friend' => $relation ? $relation->isFriend() : false,
    'blocking' => $relation ? $relation->isAccessBlocked() : false,
    'self' => $viewMemberId === $member->getId(),
    'friends_count' => $member->countFriends(),
    'self_introduction' => $selfIntroduction ? (string)$selfIntroduction : null,
  );
}

function op_api_member_profile_url($member_id)
{
  return app_url_for('pc_frontend', array('sf_route' => 'obj_member_profile', 'id' => $member_id), true);
}

function op_api_activity($activity)
{
  $viewMemberId = sfContext::getInstance()->getUser()->getMemberId();
  $member = $activity->getMember();

  return array(
    'id' => $activity->getId(),
    'member' => op_api_member($member),
    'body' => $activity->getBody(),
    'body_html' => op_auto_link_text(nl2br(op_api_force_escape($activity->getBody()))),
    'uri' => $activity->getUri(),
    'source' => $activity->getSource(),
    'source_uri' => $activity->getSourceUri(),
    'created_at' => date('r', strtotime($activity->getCreatedAt())),
  );
}

function op_api_force_escape($text)
{
  if (!sfConfig::get('sf_escaping_strategy'))
  {
    // escape body even if escaping method is disabled.
    $text = sfOutputEscaper::escape(sfConfig::get('sf_escaping_method'), $text);
  }

  return $text;
}

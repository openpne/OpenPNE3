<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * community actions.
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Kimura Youichi <kim.upsilon@gmail.com>
 */
class communityActions extends opJsonApiActions
{
  public function executeSearch(sfWebRequest $request)
  {
    $query = Doctrine::getTable('Community')->createQuery();

    if (isset($request['keyword']))
    {
      $query->andWhereLike('name', $request['keyword']);
    }

    $this->communities = $query
      ->limit(sfConfig::get('op_json_api_limit', 20))
      ->execute();

    $this->setTemplate('array');
  }

  public function executeMember(sfWebRequest $request)
  {
    if (isset($request['community_id']))
    {
      $communityId = $request['community_id'];
    }
    elseif (isset($request['id']))
    {
      $communityId = $request['id'];
    }
    else
    {
      $this->forward400('community_id parameter not specified.');
    }

    $this->members = Doctrine::getTable('Member')->createQuery('m')
      ->addWhere('EXISTS (FROM CommunityMember cm WHERE m.id = cm.member_id AND cm.is_pre = false AND cm.community_id = ?)', $communityId)
      ->limit(sfConfig::get('op_json_api_limit', 20))
      ->execute();

    $this->setTemplate('array', 'member');
  }
}

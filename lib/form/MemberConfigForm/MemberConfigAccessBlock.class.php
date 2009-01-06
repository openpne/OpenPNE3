<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * MemberConfigAccessBlockForm form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberConfigAccessBlockForm extends MemberConfigForm
{
  protected
    $category = 'accessBlock',
    $blockedId = array();

  public function configure()
  {
    $relations = MemberRelationshipPeer::retrievesByMemberIdFrom($this->member->getId());
    foreach ($relations as $relation)
    {
      if ($relation->getIsAccessBlock())
      {
        $this->blockedId[] = $relation->getMemberIdTo();
      }
    }
  }

  public function saveConfig($name, $value)
  {
    if ($name !== 'access_block')
    {
      return parent::saveConfig($name, $value);
    }

    foreach ($value as $memberId)
    {
      $relation = MemberRelationshipPeer::retrieveByFromAndTo($this->member->getId(), $memberId);
      if (!$relation)
      {
        $relation = new MemberRelationship();
        $relation->setMemberIdFrom($this->member->getId());
        $relation->setMemberIdTo($memberId);
      }

      $relation->setIsAccessBlock(in_array($memberId, $value));
      $relation->save();
    }
  }

  public function setMemberConfigWidget($name)
  {
    $result = parent::setMemberConfigWidget($name);

    if ($name === 'access_block')
    {
      $this->setDefault($name, $this->blockedId);

      $this->mergePostValidator(new sfValidatorCallback(array(
        'callback'  => array('MemberConfigAccessBlockForm', 'validate'),
        'arguments' => array('ids' => $this->blockedId),
      )));
    }

    return $result;
  }

  public static function validate($validator, $values, $arguments = array())
  {
    $result = array();

    $memberIds = array_merge($arguments['ids'], $values['access_block']);

    if (in_array(sfContext::getInstance()->getUser()->getMemberId(), $memberIds))
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    foreach ($memberIds as $memberId)
    {
      if (!$memberId)
      {
        continue;
      }

      if (!MemberPeer::retrieveByPK($memberId))
      {
        throw new sfValidatorError($validator, 'invalid');
      }

      $result[] = $memberId;
    }

    $values['access_block'] = $result;
    return $values;
  }
}

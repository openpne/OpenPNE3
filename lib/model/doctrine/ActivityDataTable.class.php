<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * activity data table
 *
 * @package    OpenPNE
 * @subpackage model
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class ActivityDataTable extends Doctrine_Table
{
  const PUBLIC_FLAG_OPEN    = 0;
  const PUBLIC_FLAG_SNS     = 1;
  const PUBLIC_FLAG_FRIEND  = 2;
  const PUBLIC_FLAG_PRIVATE = 3;

  protected static $publicFlags = array(
    self::PUBLIC_FLAG_OPEN    => 'All Users on the Web',
    self::PUBLIC_FLAG_SNS     => 'All Members',
    self::PUBLIC_FLAG_FRIEND  => '%my_friend%',
    self::PUBLIC_FLAG_PRIVATE => 'Private',
  );

  protected
    $templateConfig = null;

  public function updateActivityByTemplate($memberId, $templateName, $params = array(), $options = array())
  {
    return $this->updateActivity($memberId, '', array_merge(array(
      'template' => $templateName,
      'template_param' => $params
    ), $options));
  }

  public function updateActivity($memberId, $body, $options = array())
  {
    $object = new ActivityData();
    $object->setMemberId($memberId);
    $object->setBody($body);

    if (isset($options['template']))
    {
      $object->setTemplate($options['template']);
      if (isset($options['template_param']) && is_array($options['template_param']))
      {
        $object->setTemplateParam($options['template_param']);
      }
    }

    if (isset($options['public_flag']))
    {
      $publicFlagKeys = array_keys($this->getPublicFlags(false));
      if (!in_array($options['public_flag'], $publicFlagKeys))
      {
        throw new LogicException('Invalid public flag');
      }
      $object->setPublicFlag($options['public_flag']);
    }

    if (isset($options['in_reply_to_activity_id']))
    {
      $object->setInReplyToActivityId($options['in_reply_to_activity_id']);
    }

    if (isset($options['is_pc']) && !$options['is_pc'])
    {
      $object->setIsPc(false);
    }
    if (isset($options['is_mobile']) && !$options['is_mobile'])
    {
      $object->setIsMobile(false);
    }

    if (isset($options['uri']))
    {
      $object->setUri($options['uri']);
    }

    if (isset($options['source']))
    {
      $object->setSource($options['source']);
      if (isset($options['source_uri']))
      {
        $object->setSourceUri($options['source_uri']);
      }
    }

    $activityImages = array();
    if (isset($options['images']))
    {
      if (!is_array($options['images']))
      {
        $options['images'] = array($options['images']);
      }

      foreach ($options['images'] as $image)
      {
        $activityImage = new ActivityImage();
        if (isset($image['file_id']))
        {
          $activityImage->setFileId($image['file_id']);
        }
        elseif (isset($image['uri']) && isset($image['mime_type']))
        {
          $activityImage->setUri($image['uri']);
          $activityImage->setMimeType($image['mime_type']);
        }
        else
        {
          throw new LogicException('Invalid image data');
        }
        $activityImages[] = $activityImage;
      }
    }

    if (isset($options['foreign_table']) && isset($options['foreign_id']))
    {
      $object->setForeignTable($options['foreign_table']);
      $object->setForeignId($options['foreign_id']);
    }

    $object->save();

    foreach ($activityImages as $activityImage)
    {
      $activityImage->setActivityData($object);
      $activityImage->save();
    }

    return $object;
  }

  public function publicFlagToCaption($flag)
  {
    $i18n = sfContext::getInstance()->getI18N();
    return $i18n->__(self::$publicFlags[$flag]);
  }

  public function getPublicFlags($isI18n = true)
  {
    if (!sfConfig::get('op_activity_is_open', false) && isset(self::$publicFlags[self::PUBLIC_FLAG_OPEN]))
    {
      unset(self::$publicFlags[self::PUBLIC_FLAG_OPEN]);
    }

    $publicFlags = array();

    if ($isI18n)
    {
      $i18n = sfContext::getInstance()->getI18N();
      $termMyFriend = Doctrine::getTable('SnsTerm')->get('my_friend');

      foreach (self::$publicFlags as $key => $publicFlag)
      {
        $terms = array('%my_friend%' => $termMyFriend->pluralize()->titleize());
        $publicFlags[$key] = $i18n->__($publicFlag, $terms, 'publicFlags');
      }
    }
    else
    {
      $publicFlags = self::$publicFlags;
    }

    return $publicFlags;
  }


  public function getViewablePublicFlags($flag)
  {
    $flags = array();
    switch ($flag)
    {
      case self::PUBLIC_FLAG_PRIVATE:
        $flags[] = self::PUBLIC_FLAG_PRIVATE;
      case self::PUBLIC_FLAG_FRIEND:
        $flags[] = self::PUBLIC_FLAG_FRIEND;
      case self::PUBLIC_FLAG_SNS:
        $flags[] = self::PUBLIC_FLAG_SNS;
      case self::PUBLIC_FLAG_OPEN:
        $flags[] = self::PUBLIC_FLAG_OPEN;
        break;
    }

    return $flags;
  }

  protected function getOrderdQuery()
  {
    return $this->createQuery()->orderBy('created_at DESC');
  }

  protected function getMyMemberId()
  {
    if (is_callable(array(sfContext::getInstance()->getUser(), 'getMemberId')))
    {
      return sfContext::getInstance()->getUser()->getMemberId();
    }
    return null;
  }

  protected function getPager(Doctrine_Query $q, $page, $size)
  {
    $pager = new sfDoctrinePager('ActivityData', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  protected function addFriendActivityQuery(Doctrine_Query $q, $memberId, $isCheckApp = true)
  {
    if (null === $memberId)
    {
      $memberId = $this->getMyMemberId();
      if (null === $memberId)
      {
        throw new LogicException('The user is not login.');
      }
    }

    $dql = 'member_id = ?';
    $dqlParams = array($memberId);
    $friendIds = Doctrine::getTable('MemberRelationship')->getFriendMemberIds($memberId);
    $flags = $this->getViewablePublicFlags(self::PUBLIC_FLAG_FRIEND);
    if ($friendIds)
    {
      $query = new Doctrine_Query();
      $query->andWhereIn('member_id', $friendIds);
      $query->andWhereIn('public_flag', $flags);

      $dql .= ' OR '.implode(' ', $query->getDqlPart('where'));
      $dqlParams = array_merge($dqlParams, $friendIds, $flags);
    }
    $q->andWhere('('.$dql.')', $dqlParams);
    $q->andWhere('in_reply_to_activity_id IS NULL');

    if ($isCheckApp)
    {
      if (sfConfig::get('sf_app') == 'mobile_frontend')
      {
        $q->andWhere('is_mobile = ?', true);
      }
      else
      {
        $q->andWhere('is_pc = ?', true);
      }
    }
  }

  public function getFriendActivityList($memberId = null, $limit = 5, $isCheckApp = true)
  {
    $q = $this->getOrderdQuery();
    $this->addFriendActivityQuery($q, $memberId, $isCheckApp);
    if (null !== $limit)
    {
      $q->limit($limit);
    }
    return $q->execute();
  }

  public function getFriendActivityListPager($memberId = null, $page = 1, $size = 20, $isCheckApp = true)
  {
    $q = $this->getOrderdQuery();
    $this->addFriendActivityQuery($q, $memberId, $isCheckApp);
    return $this->getPager($q, $page, $size);
  }

  protected function addActivityQuery(Doctrine_Query $q, $memberId = null, $viewerMemberId = null, $isCheckApp = true)
  {
    if (null === $memberId)
    {
      $memberId = $this->getMyMemberId();
      if (null === $memberId)
      {
        throw new LogicException('The user is not login.');
      }
    }

    if (null === $viewerMemberId)
    {
      $viewerMemberId = $this->getMyMemberId();
    }

    if (null === $viewerMemberId)
    {
      $flag = self::PUBLIC_FLAG_OPEN;
    }
    else if ($memberId === $viewerMemberId)
    {
      $flag = self::PUBLIC_FLAG_PRIVATE;
    }
    else
    {
      $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($viewerMemberId, $memberId);
      if ($relation && $relation->isFriend())
      {
        $flag = self::PUBLIC_FLAG_FRIEND;
      }
      $flag = self::PUBLIC_FLAG_SNS;
    }

    $q->andWhere('member_id = ?', $memberId);

    $flags = $this->getViewablePublicFlags($flag);
    if (1 === count($flags))
    {
      $q->andWhere('public_flag = ?', $flags[0]);
    }
    else
    {
      $q->andWhereIn('public_flag', $flags);
    }
    $q->andWhere('in_reply_to_activity_id IS NULL');

    if ($isCheckApp)
    {
      if (sfConfig::get('sf_app') == 'mobile_frontend')
      {
        $q->andWhere('is_mobile = ?', true);
      }
      else
      {
        $q->andWhere('is_pc = ?', true);
      }
    }
  }

  public function getActivityList($memberId = null, $viewerMemberId = null, $limit = 5, $isCheckApp = true)
  {
    $q = $this->getOrderdQuery();
    $this->addActivityQuery($q, $memberId, $viewerMemberId, $isCheckApp);
    if (null !== $limit)
    {
      $q->limit($limit);
    }
    return $q->execute();
  }

  public function getActivityListPager($memberId = null, $viewerMemberId = null, $page = 1, $size = 20, $isCheckApp = true)
  {
    $q = $this->getOrderdQuery();
    $this->addActivityQuery($q, $memberId, $viewerMemberId, $isCheckApp);
    return $this->getPager($q, $page, $size);
  }

  protected function addAllMemberActivityQuery($q, $isCheckApp)
  {
    $q->whereIn('public_flag', array(self::PUBLIC_FLAG_OPEN, self::PUBLIC_FLAG_SNS));

    if ($isCheckApp)
    {
      if (sfConfig::get('sf_app') == 'mobile_frontend')
      {
        $q->andWhere('is_mobile = ?', true);
      }
      else
      {
        $q->andWhere('is_pc = ?', true);
      }
    }

    return $q;
  }

  public function getAllMemberActivityList($limit = 5, $isCheckApp = true)
  {
    $q = $this->getOrderdQuery();
    $this->addAllMemberActivityQuery($q, $isCheckApp);
    if (null !== $limit)
    {
      $q->limit($limit);
    }

    return $q->execute();
  }

  public function getAllMemberActivityListPager($page = 1, $size = 20, $isCheckApp = true)
  {
    $q = $this->getOrderdQuery();
    $this->addAllMemberActivityQuery($q, $isCheckApp);
    return $this->getPager($q, $page, $size);
  }

  public function getTemplateConfig()
  {
    if (null === $this->templateConfig)
    {
      $this->templateConfig = include(sfContext::getInstance()->getConfigCache()->checkConfig('config/activity_template.yml'));
    }

    return $this->templateConfig;
  }

  static public function filterBody(sfEvent $event, $value)
  {
    return preg_replace_callback('/%member_(\d+)_nickname%/', array(__CLASS__, 'replaceToNickname'), $value);
  }

  static protected function replaceToNickname($match)
  {
    if (1 <= count($match))
    {
      $member = Doctrine::getTable('Member')->find((int)$match[1]);
      if ($member)
      {
        return $member->getName();
      }
    }

    return opConfig::get('nickname_of_member_who_does_not_have_credentials');
  }
}

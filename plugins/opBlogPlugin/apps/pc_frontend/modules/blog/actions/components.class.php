<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * introfriend components.
 *
 * @package    OpenPNE
 * @subpackage blog
 * @author     Masato Nagasawa <nagasawa@tejimaya.com>
 */

class blogComponents extends sfComponents
{
  public function executeBlogFriend()
  {
    $this->blogRssCacheList = Doctrine::getTable('BlogRssCache')->getFriendBlogListByMemberId(
      $this->getUser()->getMemberId(),
      sfConfig::get('app_blog_component_size')
    );
  }

  public function executeBlogUser($request)
  {
    $id = 0;
    if ($request->hasParameter('id'))
    {
      $id = $request->getParameter('id');
    }
    if (!$id)
    {
      $id = $this->getUser()->getMemberId();
    }

    $this->member = Doctrine::getTable('Member')->find($id);
    $this->blogRssCacheList = Doctrine::getTable('BlogRssCache')->findByMemberId(
      $id,
      sfConfig::get('app_blog_component_size')
    );
  }
}

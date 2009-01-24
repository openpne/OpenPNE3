<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opUpdate_3_0beta4_dev_200901242112_to_3_0beta4_dev_200901250356 extends opUpdate
{
  public $table = array(
    'secure_global' => array(
      '@homepage' => 'My Home',
      'member/search' => 'Search Members',
      'community/search' => 'Search Communities',
      'member/config' => 'Settings',
      'member/invite' => 'Invite',
      'member/logout' => 'Logout',
    ),
    'default' => array(
      '@homepage' => 'Home',
      'friend/list' => 'My Friends',
      'member/profile' => 'Profile',
      'member/editProfile' => 'Edit Profile',
    ),
    'friend' => array(
      'member/profile' => 'Home',
      'friend/list' => 'Friends',
    ),
    'community' => array(
      'community/home' => 'Community Top',
      'community/join' => 'Join Community',
      'community/quit' => 'Leave Community',
    ),
  );

  public function update()
  {
    $navs = NavigationPeer::doSelect(new Criteria());
    foreach ($navs as $nav)
    {
      if (isset($this->table[$nav->getType()]) && isset($this->table[$nav->getType()][$nav->getUri()]))
      {
        $caption = $this->table[$nav->getType()][$nav->getUri()];
        $nav->setCaption($caption, 'en');
        $nav->save();
      }
    }
  }
}

<?php
/**
 * introfriend components.
 *
 * @package    OpenPNE
 * @subpackage blog
 * @author     Masato Nagasawa <nagasawa@tejimaya.com>
 */
class blogComponents extends sfComponents
{
  public function executeBlogHomeFriend()
  {
    $this->blogList = BlogPeer::getBlogListOfFriend(
      $this->getUser()->getMemberId(),
      sfConfig::get('app_blog_component_size'),
      true
    );
  }

  public function executeBlogHomeUser()
  {
    $this->member = $this->getUser()->getMember();
    $this->blogList = BlogPeer::getBlogListOfMember(
      $this->getUser()->getMemberId(),
      sfConfig::get('app_blog_component_size'),
      true
    );
  }

  public function executeBlogProfile($request)
  {
    $this->member = MemberPeer::retrieveByPk($this->id);
    $this->blogList = BlogPeer::getBlogListOfMember(
      $this->id,
      sfConfig::get('app_blog_component_size'),
      true
    );
  }
}

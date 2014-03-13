<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opBlogPluginFrontendRouteCollection
 *
 * @package    opBlogPlugin
 * @subpackage routing
 * @author     Masato Nagasawa <nagasawa@tejimaya.com>
 */
class opBlogPluginFrontendRouteCollection extends opBlogPluginBaseRouteCollection
{
  protected function generateRoutes()
  {
    return array(
      'blog_index' => new sfRoute(
        '/blog',
        array('module' => 'blog', 'action' => 'index')
      ),
      'blog_user' => new sfRoute(
        '/blog/user',
        array('module' => 'blog', 'action' => 'user')
      ),
      'blog_user_profile' => new sfDoctrineRoute(
        '/blog/user/:id',
        array('module' => 'blog', 'action' => 'user'),
        array('id' => '\d+'),
        array('model' => 'Member', 'type' => 'object')
      ),
      'blog_friend' => new sfRoute(
        '/blog/friend',
        array('module' => 'blog', 'action' => 'friend')
      )
    );
  }
}

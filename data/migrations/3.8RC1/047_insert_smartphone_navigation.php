<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision47_InsertSmaertphoneNavigation extends Doctrine_Migration_Base
{
  public function up()
  {
    $navigation = new Navigation();
    $navigation->setType('smartphone_default');
    $navigation->setUri('@homepage');
    $navigation->setSortOrder(0);
    $navigation->Translation['ja_JP']->caption = 'マイホーム';
    $navigation->Translation['en']->caption = 'My Home';
    $navigation->save();
    $navigation->free();

    $navigation = new Navigation();
    $navigation->setType('smartphone_default');
    $navigation->setUri('@member_profile_mine');
    $navigation->setSortOrder(10);
    $navigation->Translation['ja_JP']->caption = 'プロフィール確認';
    $navigation->Translation['en']->caption = 'Profile';
    $navigation->save();
    $navigation->free();

    $navigation = new Navigation();
    $navigation->setType('smartphone_default');
    $navigation->setUri('@member_search');
    $navigation->setSortOrder(20);
    $navigation->Translation['ja_JP']->caption = 'メンバー検索';
    $navigation->Translation['en']->caption = 'Member Search';
    $navigation->save();
    $navigation->free();

    $navigation = new Navigation();
    $navigation->setType('smartphone_default');
    $navigation->setUri('@community_search');
    $navigation->setSortOrder(30);
    $navigation->Translation['ja_JP']->caption = 'コミュニティ検索';
    $navigation->Translation['en']->caption = 'Community Search';
    $navigation->save();
    $navigation->free();

    $navigation = new Navigation();
    $navigation->setType('smartphone_default');
    $navigation->setUri('@member_logout');
    $navigation->setSortOrder(40);
    $navigation->Translation['ja_JP']->caption = 'ログアウト';
    $navigation->Translation['en']->caption = 'Logout';
    $navigation->save();
    $navigation->free();

    $navigation = new Navigation();
    $navigation->setType('smartphone_insecure');
    $navigation->setUri('@global_privacy_policy');
    $navigation->setSortOrder(30);
    $navigation->Translation['ja_JP']->caption = 'プライバシーポリシー';
    $navigation->Translation['en']->caption = 'Privacy Policy';
    $navigation->save();
    $navigation->free();

    $navigation = new Navigation();
    $navigation->setType('smartphone_insecure');
    $navigation->setUri('@globsl_user_agreement');
    $navigation->setSortOrder(40);
    $navigation->Translation['ja_JP']->caption = '利用規約';
    $navigation->Translation['en']->caption = 'User Agreement';
    $navigation->save();
    $navigation->free();
  }
}

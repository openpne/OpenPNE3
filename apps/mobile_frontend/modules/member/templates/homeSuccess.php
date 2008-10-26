<?php include_page_title(OpenPNEConfig::get('sns_name')) ?>

<?php
$info = array(
  'name' => $sf_user->getMember()->getName(),
);

$menu = array(
  'C' => array(
    'friend/list' => sprintf('ﾏｲﾌﾚﾝﾄﾞ(%s)', $sf_user->getMember()->countFriendsRelatedByMemberIdTo()),
    'community/joinlist' => sprintf('参加ｺﾐｭﾆﾃｨ(%s)', $sf_user->getMember()->countCommunityMembers()),
  ),
  'D' => array(
    'member/profile' => 'ﾌﾟﾛﾌｨｰﾙ'
  ),
);
include_home_header_box_parts('menu', $info, $menu)
?>

<br>

<?php
$list = array(
  'member/editProfile' => array(
    'link' => 'ﾌﾟﾛﾌｨｰﾙ変更',
  ),
);
include_list_box('profileEdit', $list, '%link%', array('title' => 'ﾌﾟﾛﾌｨｰﾙ変更'))
?>

<?php
$list = array(
  'member/configUID' => array(
    'link' => 'かんたんﾛｸﾞｲﾝ設定',
  ),
);
include_list_box('configEdit', $list, '%link%', array('title' => '設定変更'))
?>

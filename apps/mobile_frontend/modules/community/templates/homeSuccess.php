<?php include_page_title($community->getName()) ?>

<table width="100%" bgcolor="#EEEEFF">
<tr><td colspan="2" align="center">
<hr color="#0D6DDF" size="3">
</td></tr>

<tr><td align="center" width="50%" valign="top">
</td>

<td valign="top">
<font color="#999966">ID:</font><br>
<?php echo $community->getId() ?><br>
</td>
</tr>

<tr><td colspan="2" align="center">
<hr color="#0d6ddf" size="3">
</td></tr>

<tr><td colspan="2">
<?php if ($isEditCommunity) : ?>
<?php echo link_to('このｺﾐｭﾆﾃｨを編集する', 'community/edit?id=' . $community->getId()) ?><br>
<?php endif; ?>
<?php if (!$isAdmin) : ?>
<?php if ($isCommunityMember) : ?>
<?php echo link_to('このｺﾐｭﾆﾃｨを退会する', 'community/quit?id=' . $community->getId()) ?><br>
<?php else : ?>
<?php echo link_to('このｺﾐｭﾆﾃｨに参加する', 'community/join?id=' . $community->getId()) ?><br>
<?php endif; ?>
<?php endif; ?>
<hr color="#0d6ddf" size="3">
</td></tr>
</table>

<br>

<?php
$list = array();
foreach ($community->getCommunityMembers() as $communityMember) {
  $member = $communityMember->getMember();
  $list[] = link_to($member->getName(), 'member/profile?id='.$member->getId());
}
$options = array(
  'title' => 'ｺﾐｭﾆﾃｨﾒﾝﾊﾞｰ',
  'border' => true,
  'moreInfo' => array(
    link_to('<font color="#0c5f0f">⇒</font>もっと見る', 'community/memberList?id='.$community->getId()),
  ),
);
include_list_box('communityMember', $list, $options);
?>

</ul>

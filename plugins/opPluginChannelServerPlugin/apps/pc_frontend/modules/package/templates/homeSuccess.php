<?php use_helper('opJavascript') ?>
<?php slot('op_sidemenu'); ?>
<?php
$options = array(
  'object'      => $package,
  'name_method' => 'getName',
);
op_include_parts('memberImageBox', 'packageImageBox', $options);
?>

<div style = "
background-color:#AAEEFF;
color:#0000FF;
font-size:20px;
font-weight:bold;
margin:10px;
padding:10px;
text-align:center;
" id="plugin_user">
<span id="plugin_user_count"><?php echo $package->countUsers() ?></span><br /><span>users</span>
<p style="margin-top: 10px; text-align: center; font-size: 9px; color: #000;">
<?php if ($package->isAllowed($sf_user->getRawValue()->getMember(), 'countUser')): ?>
<?php
$form = new sfForm();
$_ajax_parameter = '"'.sfForm::getCSRFFieldName().'='.$form->getDefault(sfForm::getCSRFFieldName()).'"';
?>
<?php echo link_to_remote(__('I don\'t use this plugin'), array(
  'url'      => '@package_use?name='.$package->name,
  'complete' => 'updateUser(request)',
  '404'      => 'alert("'.__('CSRF attack detected.').'")',
  'with'     => $_ajax_parameter,
), array('id' => 'package_unuse_link',
'style' => 'display:'.($package->isUser($sf_user->getMemberId()) ? 'inline' : 'none'))) ?>
<?php echo link_to_remote(__('I use this plugin'), array(
  'url'     => '@package_use?name='.$package->name,
  'complete' => 'updateUser(request)',
  '404'      => 'alert("'.__('CSRF attack detected.').'")',
  'with'     => $_ajax_parameter,
), array('id' => 'package_use_link',
'style' => 'display:'.(!$package->isUser($sf_user->getMemberId()) ? 'inline' : 'none'))) ?>
<?php else: ?>
<?php echo __('Please login to vote for this plugin') ?>
<?php endif; ?>
</p>
</div>
<?php echo javascript_tag('
function updateUser(ajax)
{
  var json = ajax.responseJSON;

  Element.update("plugin_user_count", json[0]);

  if (json[1]) {
    Element.hide("package_use_link");
    Element.show("package_unuse_link");
  } else {
    Element.show("package_use_link");
    Element.hide("package_unuse_link");
  }
}
') ?>

<?php
$options = array(
  'title' => __('Plugin Developers'),
  'list'  => $package->getMembers(),
  'crownIds' => $package->getLeadMemberIds()->getRawValue(),
  'link_to' => '@member_profile?id=',
  'moreInfo' => array(link_to(sprintf('%s(%d)', __('Show all'), $package->countMembers()), '@package_list_member?name='.$package->name)),
);

if ($package->isAllowed($sf_user->getRawValue()->getMember(), 'manageMember'))
{
  $options['moreInfo'][] = link_to(__('Manage developers'), '@package_manageMember?name='.$package->name);
}

op_include_parts('nineTable', 'developerList', $options);
?>
<?php end_slot(); ?>

<?php
$list = array(
  __('Category')       => $package->Category,
  __('Summary')        => $package->summary,
  __('License')        => $package->license,
  __('Description')    => nl2br($package->description),
);

if ($package->repository)
{
  $list[__('Repository URL')] = link_to($package->repository, $package->repository);
}
if ($package->bts)
{
  $list[__('BTS URL')] = link_to($package->bts, $package->bts);
}

$options = array(
  'title' => __('Detail of this plugin'),
  'list' => $list,
  'moreInfo' => array(),
);

if ($package->isAllowed($sf_user->getRawValue()->getMember(), 'edit'))
{
  $options['moreInfo'][] = link_to(__('Edit this plugin information'), 'package_edit', $package);
}

if ($package->isAllowed($sf_user->getRawValue()->getMember(), 'request'))
{
  $options['moreInfo'][] = link_to(__('Join this plugin developer team'), 'package_join', $package);
}
op_include_parts('listBox', 'packageInformation', $options);
?>

<div id="releaseList" class="dparts homeRecentList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('Releases of this plugin') ?></h3></div>
<div class="block">
<ul class="articleList">
<?php foreach ($package->getReleases() as $v): ?>
<li><span class="date"><?php echo op_format_date($v->created_at, 'XShortDateJa') ?></span>
<?php echo link_to($v->version, 'release_detail', $v) ?>
</li>
<?php endforeach; ?>
</ul>
<div class="moreInfo">
<ul class="moreInfo">
<?php if ($package->isAllowed($sf_user->getRawValue()->getMember(), 'release')): ?>
<li>
<?php echo link_to(__('Release Packages'), 'package_add_release', $package) ?>
</li>
<?php endif; ?>
<li>
<?php echo link_to(__('More info'), 'package_list_release', $package) ?>
</li>
</ul>
</div>
</div>
</div></div>


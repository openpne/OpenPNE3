<?php slot('op_sidemenu'); ?>
<?php include_partial('pluginInformationBar', array('package' => $package)) ?>
<?php end_slot(); ?>

<style type="text/css">
.example
{
  margin-bottom: 1em;
  padding: 1em;
  background-color: #CCCCCC;
}

.example dl
{
  margin-top: 1em;
}

.example dt
{
  font-weight: bold;
}

.example dd
{
  margin-left: 2em;
}

</style>

<div id="AddReleaseByTgz" class="dparts form">
<div class="parts">

<div class="partsHeading"><h3><?php echo __('Add release by package file') ?></h3></div>

<p><?php echo __('You can create a package file by the opPlugin:release task.') ?></p>
<div class="example">
<code>$ symfony opPlugin:release <?php echo $package->name ?> ~/</code>

<?php if (opPluginChannelServerToolkit::getConfig('channel_name') !== opPluginManager::OPENPNE_PLUGIN_CHANNEL): ?>
<br />
<br />
[<?php echo __('OpenPNE 3.5.0 +') ?>]<br />
<code>$ symfony opPlugin:release <?php echo $package->name ?> ~/ --channel=<?php echo opPluginChannelServerToolkit::getConfig('channel_name') ?></code>
<?php endif; ?>
<dl>
<dt><?php echo __('The first argument')?></dt><dd><?php echo __('The name of plugin which you want to release') ?></dd>
<dt><?php echo __('The second argument')?></dt><dd><?php echo __('The path to directory which you want to save the generated package file') ?></dd>
<dt><?php echo __('The --channel option (OpenPNE 3.5.0 +)')?></dt><dd><?php echo __('The channel server which you want to release') ?></dd>
</dl>
</div>

<form action="<?php echo url_for('package_add_release', $package) ?>" method="post" enctype="multipart/form-data">
<table>
<?php $form->renderGlobalErrors(); ?>
<?php echo $form['tgz_file']->renderRow() ?>
</table>
<div class="operation">
<ul class="moreInfo button">
<li>
<?php echo $form->renderHiddenFields(); ?>
<input type="submit" value="<?php echo __('Send') ?>" class="input_submit" />
</li>
</ul>
</div>
</form>

</div>
</div>

<div id="AddReleaseBySvn" class="dparts">
<div class="parts">

<div class="partsHeading"><h3><?php echo __('Add release by Subversion repository') ?></h3></div>

<p><?php echo __('Before your releasing, you need to create / update and commit a package definition file (package.xml) to your repository.') ?></p>
<p><?php echo __('You can generate a definition file by the following command:') ?></p>
<div class="example">
<code>$ symfony opPlugin:release <?php echo $package->name ?> 1.0.0 "First stable release."</code>
<?php if (opPluginChannelServerToolkit::getConfig('channel_name') !== opPluginManager::OPENPNE_PLUGIN_CHANNEL): ?>
<br />
<br />
[<?php echo __('OpenPNE 3.5.0 +') ?>]<br />
<code>$ symfony opPlugin:release <?php echo $package->name ?> 1.0.0 "First stable release." --channel=<?php echo opPluginChannelServerToolkit::getConfig('channel_name') ?></code>
<?php endif; ?>
<dl>
<dt><?php echo __('The first argument')?></dt><dd><?php echo __('The name of plugin which you want to release') ?></dd>
<dt><?php echo __('The second argument')?></dt><dd><?php echo __('The version of plugin which you want to release') ?></dd>
<dt><?php echo __('The third argument')?></dt><dd><?php echo __('The release note') ?></dd>
<dt><?php echo __('The --channel option (OpenPNE 3.5.0 +)')?></dt><dd><?php echo __('The channel server which you want to release') ?></dd>
</dl>
</div>

<form action="<?php echo url_for('package_add_release', $package) ?>" method="post">
<table>
<?php $form->renderGlobalErrors(); ?>
<?php echo $form['svn_url']->renderRow() ?>
</table>
<div class="operation">
<ul class="moreInfo button">
<li>
<?php echo $form->renderHiddenFields(); ?>
<input type="submit" value="<?php echo __('Send') ?>" class="input_submit" />
</li>
</ul>
</div>
</form>

</div>
</div>

<div id="AddReleaseByGit" class="dparts">
<div class="parts">

<div class="partsHeading"><h3><?php echo __('Add release by Git repository') ?></h3></div>

<p><?php echo __('Before your releasing, you need to create / update and push a package definition file (package.xml) to your repository.') ?></p>
<p><?php echo __('You can generate a definition file by the following command:') ?></p>
<div class="example">
<code>$ symfony opPlugin:release <?php echo $package->name ?> 1.0.0 "First stable release."</code>
<?php if (opPluginChannelServerToolkit::getConfig('channel_name') !== opPluginManager::OPENPNE_PLUGIN_CHANNEL): ?>
<br />
<br />
[<?php echo __('OpenPNE 3.5.0 +') ?>]<br />
<code>$ symfony opPlugin:release <?php echo $package->name ?> 1.0.0 "First stable release." --channel=<?php echo opPluginChannelServerToolkit::getConfig('channel_name') ?></code>
<?php endif; ?>
<dl>
<dt><?php echo __('The first argument')?></dt><dd><?php echo __('The name of plugin which you want to release') ?></dd>
<dt><?php echo __('The second argument')?></dt><dd><?php echo __('The version of plugin which you want to release') ?></dd>
<dt><?php echo __('The third argument')?></dt><dd><?php echo __('The release note') ?></dd>
<dt><?php echo __('The --channel option (OpenPNE 3.5.0 +)')?></dt><dd><?php echo __('The channel server which you want to release') ?></dd>
</dl>
</div>

<form action="<?php echo url_for('package_add_release', $package) ?>" method="post">
<table>
<?php $form->renderGlobalErrors(); ?>
<?php echo $form['git_url']->renderRow() ?>
<?php echo $form['git_commit']->renderRow() ?>
</table>
<div class="operation">
<ul class="moreInfo button">
<li>
<?php echo $form->renderHiddenFields(); ?>
<input type="submit" value="<?php echo __('Send') ?>" class="input_submit" />
</li>
</ul>
</div>
</form>

</div>
</div>

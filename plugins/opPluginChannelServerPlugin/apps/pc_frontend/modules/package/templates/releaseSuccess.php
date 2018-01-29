<?php slot('op_sidemenu'); ?>
<?php include_partial('pluginInformationBar', array('package' => $release->Package)) ?>
<?php end_slot(); ?>

<?php

op_include_parts('listBox', 'releaseInfoList', array(
  'title' =>  __('Detail of this release'),
  'list' => array(
    __('Plugin') => $release->Package->name,
    __('Version') => $release->version,
    __('Stability') => __($release->stability),
    __('Release Note') => nl2br($info['notes']),
    __('Installation') => 
      __('Install the plugin:').'<br />
      <code>$ ./symfony opPlugin:install '.$release->Package->name.' -r '.$release->version.' --channel='.opPluginChannelServerToolkit::getConfig('channel_name').'</code><br />
      <br />'.
      __('Migrate your model and database:').'<br />
      <code>$ ./symfony openpne:migrate --target='.$release->Package->name.'</code><br />
      ',
    __('Download') => link_to(
      url_for('@plugin_download_tgz?version='.$release->version.'&name='.$release->Package->name, true),
      '@plugin_download_tgz?version='.$release->version.'&name='.$release->Package->name),
  ),
));

if ($release->isAllowed($sf_user->getRawValue()->getMember(), 'delete'))
{
  op_include_form('removeRelease', $form, array(
    'title'  => __('Do you want to delete this release?'),
    'button' => __('Delete'),
    'url'    => url_for('@release_delete?id='.$release->id),
  ));
}

<style type="text/css">
.example
{
  margin-bottom: 1em;
  padding: 1em;
  background-color: #CCCCCC;
}
</style>

<?php

include_box('usageBox', __('How to use this plugin channel server'),
  '<p>'.__('If you want to use plugins in this channel server, please install by the following command:').'</p>'
  .'<div class="example"><code>$ ./symfony opPlugin:install [plugin-name] --channel='.opPluginChannelServerToolkit::getConfig('channel_name').'</code></div>'
  .'<p>'.__('You can set this server as your default channel server (only OpenPNE 3.5.0 +). Edit your config/OpenPNE.yml like:').'</p>'
  .'<div class="example"><code>default_plugin_channel_server: '.opPluginChannelServerToolkit::getConfig('channel_name').'</code></div>'
);

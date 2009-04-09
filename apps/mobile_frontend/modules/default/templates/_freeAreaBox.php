<?php
$options = array();
if($gadget->getConfig('title'))
{
  $options['title'] = $gadget->getConfig('title');
}
op_include_box('freeArea', $sf_data->getRaw('gadget')->getConfig('value'), $options);

<?php
$options = array();
if ($gadget->getConfig('title'))
{
  $options['title'] = $gadget->getConfig('title');
}
op_include_box('freeArea_'.$gadget->getId(), $gadget->getRawValue()->getConfig('value'), $options)
?>

<?php

//------------------------------------------------------------
// register config handler for test
class TestActivityTemplateConfigHandler extends sfConfigHandler
{
  public function execute($configFiles)
  {
    $retval = "<?php return ".var_export(array(
      'test_template' => 'Test test %member_1_nickname% test, %foo%!!!'
    ), true).";";

    return $retval;
  }
}
sfContext::getInstance()->getConfigCache()->registerConfigHandler('config/activity_template.yml', 'TestActivityTemplateConfigHandler');

$dispacher = sfContext::getInstance()->getEventDispatcher();
$listeners = $dispacher->getListeners('op_activity.template.filter_body');
foreach ($listeners as $listener)
{
  if (in_array($listener) && 'ActivityDataTable' === $listener[0])
  {
    $dispacher->disconnect('op_activity.template.filter_body', $listener);
  }
}

$listeners = $dispacher->getListeners('op_activity.filter_body');
foreach ($listeners as $listener)
{
  $dispacher->disconnect('op_activity.filter_body', $listener);
}

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


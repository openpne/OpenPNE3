<?php

include_once dirname(__FILE__).'/../../bootstrap/unit.php';

class MyRecord extends opDoctrineRecord
{
  public function definedMethod()
  {
    return 'original';
  }
}

$_app = 'pc_frontend';
$_env = 'test';

$configuration = ProjectConfiguration::getApplicationConfiguration($_app, $_env, true);

$t = new lime_test(3, new lime_output_color());

// --

Doctrine_Manager::connection('mock://example/example');

$configuration->getEventDispatcher()->connect('record.method_not_found', function ($event) {
  if ($event->getSubject() instanceof MyRecord)
  {
    if ('appendedMethod' === $event['method'])
    {
      $event->setReturnValue('appended');

      return true;
    }
    elseif ('definedMethod' === $event['method'])
    {
      $event->setReturnValue('overwritten');

      return true;
    }
  }
});

$conn = Doctrine_Manager::getInstance()->getCurrentConnection();
$my = new MyRecord(new Doctrine_Table('MyRecord', $conn));
$my->setEventDispatcher($configuration->getEventDispatcher());
$t->is($my->appendedMethod(), 'appended', '"appendedMethod()" returns "appended"');
$t->is($my->definedMethod(), 'original', '"definedMethod()" returns "orignal" because overwriting this method is ignored');

$e = null;
try
{
  $my->unknownMethod();
}
catch (Doctrine_Record_UnknownPropertyException $e)
{
}
$t->ok($e instanceof Doctrine_Exception, '"unknownMethod()" throws original exception');

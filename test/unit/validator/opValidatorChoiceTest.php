<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new lime_test(9, new lime_output_color());

$t->diag('opValidatorChoice');

$t->diag('->clean()');

$v = new opValidatorChoice(array(
  'choices' => array('00', '000', '0.0'),
));

try
{
  $v->clean('0');
  $t->fail('->clean() throws a sfValidatorError if not in choices');
  $t->skip('', 1);
}
catch (Exception $e)
{
  $t->pass('->clean() throws a sfValidatorError if not in choices');
  $t->is($e->getCode(), 'invalid', '->clean() throws a sfValidatorError');
}

$t->is($v->clean('00'), '00', '->clean() accepts in choices');
$t->is($v->clean('000'), '000', '->clean() accepts in choices');
$t->is($v->clean('0.0'), '0.0', '->clean() accepts in choices');


$v = new opValidatorChoice(array(
  'choices' => array('011', '11.0'),
));

try
{
  $v->clean('11');
  $t->fail('->clean() throws a sfValidatorError if not in choices');
  $t->skip('', 1);
}
catch (Exception $e)
{
  $t->pass('->clean() throws a sfValidatorError if not in choices');
  $t->is($e->getCode(), 'invalid', '->clean() throws a sfValidatorError');
}

$t->is($v->clean('011'), '011', '->clean() accepts in choices');
$t->is($v->clean('11.0'), '11.0', '->clean() accepts in choices');

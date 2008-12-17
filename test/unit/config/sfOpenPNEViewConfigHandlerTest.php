<?php

require_once dirname(__FILE__).'/../../bootstrap/unit.php';

$t = new lime_test(null, new lime_output_color());

function strip_cl($content)
{
  return str_replace(array("\r\n", "\n", "\r"), '', $content);
}

class myHandler extends sfOpenPNEViewConfigHandler
{
  public function setConfiguration($config)
  {
    $this->yamlConfig = self::mergeConfig($config);
  }

  public function addCustomizes($viewName = '')
  {
    return parent::addCustomizes($viewName);
  }
}

$h = new myHandler();


$t->diag('addCustomizes() basic customize addition');

$h->setConfiguration(array(
  'myView' => array('customize' => array(
    'first' => array('member', 'first'),
  )),
));

$content = <<<EOF
  \$this->setCustomize('first', '', 'first', array(), array(), array(), false);  if (sfConfig::get('sf_logging_enabled')) \$this->context->getEventDispatcher()->notify(new sfEvent(\$this, 'application.log', array(sprintf('Set customize "%s" (%s/%s)', 'first', '', 'first'))));
EOF;
$t->is(strip_cl($h->addCustomizes('myView')), strip_cl($content), 'addCustomizes() basic customize addition');


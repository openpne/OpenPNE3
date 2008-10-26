<?php

/**
 * sfWidgetFormSchemaFormatterMobile
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfWidgetFormSchemaFormatterMobile extends sfWidgetFormSchemaFormatter
{
  protected
    $rowFormat       = "<br>%label%:<br>%field%%help%%hidden_fields%%error%<br>",
    $errorRowFormat  = "%errors%<br>",
    $helpFormat      = '<br>%help%';
}

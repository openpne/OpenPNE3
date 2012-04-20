<?php


/**
 * install component.
 *
 * @package    OpenPNE
 * @subpackage plugin
 * @author     Hiromi Hishida<info@77-web.com>
 */
class installComponent extends sfComponent
{
  public function execute($request)
  {
    $this->form = new PluginInstallForm();
  }
}
<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfWidgetFormSchemaFormatterMobile
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfWidgetFormSchemaFormatterMobile extends opWidgetFormSchemaFormatter
{
  protected
    $rowFormat                 = '',
    $errorRowFormat            = '',
    $helpFormat                = '%help%',
    $decoratorFormat           = '',
    $errorRowFormatInARow      = "<br>%error%\n",
    $namedErrorRowFormatInARow = "<br>%name%: %error%\n"; 

  public function __construct(sfWidgetFormSchema $widgetSchema)
  {
    $this->rowFormat = '<font color="'.opColorConfig::get('core_color_19').'">%label%:</font><br>%field%%help%%hidden_fields%%error%<br><br>';
    $this->errorListFormatInARow = '<font color="'.opColorConfig::get('core_color_22').'">'."\n".'%errors%</font>';
    $this->helpFormat = '<br><font color="'.opColorConfig::get('core_color_19').'">%help%</font>';

    $this->setWidgetSchema($widgetSchema);
  }
}

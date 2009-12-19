<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfWidgetFormSchemaFormatterPc
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @author     Rimpei Ogawa <ogawa@tejimaya.com>
 */
class sfWidgetFormSchemaFormatterPc extends opWidgetFormSchemaFormatter
{
  protected
    $helpFormat            = '<div class="help">%help%</div>',
    $errorListFormatInARow = "  <div class=\"error\"><ul class=\"error_list\">\n%errors%  </ul></div>\n";
}

<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * This class is for keeping backward compatibility.
 *
 * If you want to add new feature to this class, please add this to
 * the opExecutionFilter class, a parent class of this class.
 * And of course using this class is deprecated. You should not begin to
 * use this class, and you have to replace the code that is using this class.
 * 
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class sfOpenPNEExecutionFilter extends opExecutionFilter
{
}

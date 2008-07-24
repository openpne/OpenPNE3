<?php

/**
 * sfOpenPNEAuthContainer_PCAddress will handle credential for OpenPNE.
 *
 * @package    symfony
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
abstract class sfOpenPNEAuthContainer
{
  /**
   * Fetch data from storage container
   *
   * @param  sfForm $form
   * @return int    memberId
   */
  abstract public function fetchData($form);
}

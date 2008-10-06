<?php

/**
 * sfOpenPNEActionFilter
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNEActionFilter extends sfFilter
{
  /**
   * @see sfFilter
   */
  final public function execute($filterChain)
  {
    // executes pre filter
    $preFilterMethod = 'pre'.ucfirst($this->getCurrentAction()->getActionName());
    if (is_callable(array($this, $preFilterMethod))) {
      $this->$preFilterMethod();
    }

    $filterChain->execute();

    // executes post filter
    $postFilterMethod = 'post'.ucfirst($this->getCurrentAction()->getActionName());
    if (is_callable(array($this, $postFilterMethod))) {
      $this->$postFilterMethod();
    }
  }

 /**
  * Gets the current action instance.
  *
  * @return sfAction
  */
  protected function getCurrentAction()
  {
    return $this->context->getController()->getActionStack()->getLastEntry()->getActionInstance();
  }
}

<?php 

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * default community form
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Shogo Kawahara <kawahara@tejimaya.com>
 */
class DefaultCommunityForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'id'  => new sfWidgetFormInput(),
    ));

    $validatorCallback = new sfValidatorCallback(array('callback' => array($this, 'checkCommunityId')));
    $validatorCallback->addMessage('already_default', 'This community is already the default.');
    $validator = new sfValidatorAnd(array(new sfValidatorInteger(), $validatorCallback));

    $this->setValidators(array(
      'id' => $validator,
    ));

    $this->widgetSchema->setNameFormat('community[%s]');
  }

  public function checkCommunityId(sfValidatorBase $validator, $value, $arguments)
  {
    $community = Doctrine::getTable('Community')->find($value);

    if (!$community)
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    if ((bool)$community->getConfig('is_default'))
    {
      throw new sfValidatorError($validator, 'already_default');
    }

    return $value;
  }
}

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

    $this->setValidators(array(
      'id' => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('community[%s]');
  }

  public function save()
  {
    $community = Doctrine::getTable('Community')->find($this->getValue('id'));

    if (!$community)
    {
      return false;
    }

    $communityConfig = Doctrine::getTable('CommunityConfig')->retrieveByNameAndCommunityId('is_default', $community->getId());

    if (!$communityConfig)
    {
      $communityConfig = new CommunityConfig();
    }
    $communityConfig->setCommunity($community);
    $communityConfig->setName('is_default');
    $communityConfig->setValue(1);
    $communityConfig->save();

    return true;
  }
}

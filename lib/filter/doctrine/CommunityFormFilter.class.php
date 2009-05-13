<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Community filter form.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class CommunityFormFilter extends BaseCommunityFormFilter
{
  public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  {
    return parent::__construct($defaults, $options, false);
  }

  public function configure()
  {
    $q = Doctrine::getTable('CommunityCategory')->createQuery()->where('lft > 1');
    $this->setWidgets(array(
      'name'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'community_category_id' => new sfWidgetFormDoctrineChoice(array(
        'model'       => 'CommunityCategory',
        'add_empty'   => sfContext::getInstance()->getI18N()->__('All categories'),
        'query'    => $q,
        'default' => 0)),
    ));

    $this->setValidators(array(
      'name'                  => new sfValidatorPass(),
      'community_category_id' => new sfValidatorPass(),
    ));

    $this->widgetSchema->setLabel('community_category_id', 'Community Category');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    $this->widgetSchema->setNameFormat('community[%s]');
    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('form_community');
  }
}

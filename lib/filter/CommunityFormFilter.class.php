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
    unset($this['file_id'], $this['created_at'], $this['updated_at']);
    $c = new Criteria();
    $c->add(CommunityCategoryPeer::LEFT_COL, 1, Criteria::GREATER_THAN);
    $this->setWidgets(array(
      'name'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'community_category_id' => new sfWidgetFormPropelChoice(array(
        'model'       => 'CommunityCategory',
        'add_empty'   => sfContext::getInstance()->getI18N()->__('All categories'),
        'criteria'    => $c,
        'default' => 0)),
    ));

    $this->widgetSchema->setLabel('community_category_id', 'Community Category');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    $this->widgetSchema->setNameFormat('community[%s]');
    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('form_community');
  }
}

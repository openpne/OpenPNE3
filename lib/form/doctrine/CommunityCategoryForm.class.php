<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * CommunityCategory form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class CommunityCategoryForm extends BaseCommunityCategoryForm
{
  public function configure()
  {
    unset($this['id'], $this['sort_order'], $this['lft'], $this['rgt'], $this['level'], $this->widgetSchema['tree_key']);

    $obj = $this->isNew() ? $this->getOption('category') : $this->getObject();
    if ($obj instanceof CommunityCategory)
    {
      $this->setWidget('tree_key', new sfWidgetFormInputHidden(array('default' => $obj->getTreeKey())));
    }

    $this->widgetSchema->setLabel('name', 'Category Name');

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('form_community');
    unset($this['created_at'], $this['updated_at']);
  }
}

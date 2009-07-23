<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Banner form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Masato Nagasawa <nagasawa@tejimaya.com>
 */
class BannerForm extends BaseBannerForm
{
  private $bannerImageIdList = array();

  public function setup()
  {
    unset($this['id'], $this['name']);

    $id = $this->getObject()->getId();

    // banner use image
    $bannerImageList = Doctrine::getTable('BannerImage')->createQuery()->execute();
    foreach ($bannerImageList as $bannerImage)
    {
      $bannerUseImage = Doctrine::getTable('BannerUseImage')->retrieveByBannerAndImageId($id, $bannerImage->getId());
      $name = 'banner_use_image_id]['.$bannerImage->getId();
      $this->setWidget(
        $name,
        new sfWidgetFormChoice(array(
          'choices'  => array('1' => '表示する', '0' => '表示しない'),
          'expanded' => true,
          'default' => $bannerUseImage ? '1' : '0'))
      );
      $this->setValidator(
        $name,
        new sfValidatorChoice(array('choices' => array('1', '0')))
      );
    }
    // html
    $this->setWidget(
      'html',
      new sfWidgetFormTextarea(array(), array('cols' => 72, 'rows' => 5))
    );
    $this->setValidator('html', new sfValidatorPass());

    // is use html
    $this->setWidget(
      'is_use_html',
      new sfWidgetFormChoice(array(
        'choices'  => array('0' => 'is_html', '1' => 'is_no_html'),
        'expanded' => true,
        'default' => $this->getObject()->getIsUseHtml() ? '1' : '0'))
    );
    $this->setValidator(
      'is_use_html',
      new sfValidatorChoice(array('choices' => array('1', '0')))
    );

    $this->widgetSchema->setNameFormat('banner[%s]');
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    if (isset($taintedValues['banner_use_image_id']))
    {
      $this->bannerImageIdList = $taintedValues['banner_use_image_id'];
      unset($taintedValues['banner_use_image_id']);
    }
    foreach($this->bannerImageIdList as $key => $bannerUseImageId)
    {
      $taintedValues['banner_use_image_id]['.$key] = $bannerUseImageId;
    }

    return parent::bind($taintedValues, $taintedFiles);
  }

  public function isValid()
  {
    if (!$this->getValue('is_use_html') && !count($this->bannerImageIdList))
    {
      return false;
    }

    return parent::isValid();
  }

  public function save()
  {
    // use banner image
    foreach($this->bannerImageIdList as $bannerImageId => $isUse)
    {
      $bannerUseImage = Doctrine::getTable('BannerUseImage')->retrieveByBannerAndImageId(
        $this->getObject()->getId(),
        $bannerImageId
      );
      if ($isUse)
      {
        if (!$bannerUseImage)
        {
          $bannerUseImage = new BannerUseImage();
        }
        $bannerUseImage->setBannerId($this->getObject()->getId());
        $bannerUseImage->setBannerImageId($bannerImageId);
        $bannerUseImage->save();
        continue;
      }
      if ($bannerUseImage)
      {
        $bannerUseImage->delete();
      }
    }

    return parent::save();
  }
}

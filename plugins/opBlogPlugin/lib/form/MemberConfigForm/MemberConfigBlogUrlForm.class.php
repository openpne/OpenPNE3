<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberConfigBlogUrlForm form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Masato Nagasawa <nagasawa@tejimaya.net>
 */
class MemberConfigBlogUrlForm extends MemberConfigForm
{
  protected $category = 'blogUrl';

  public function setMemberConfigWidget($name)
  {
    $result = parent::setMemberConfigWidget($name);

    if ($name === 'blog_url')
    {
      $this->widgetSchema['blog_url']->setAttributes(array('size' => 57));
      $this->mergePostValidator(new sfValidatorCallback(array(
        'callback'  => array($this, 'validate'),
      )));
    }

    return $result;
  }

  public function validate($validator, $value)
  {
    if ($value['blog_url'] !== "")
    {
      $root = opBlogPlugin::getFeedByUrl($value['blog_url']);
      if (!$root)
      {
        $error = new sfValidatorError($validator, 'URL is invalid.');
        throw new sfValidatorErrorSchema($validator, array('blog_url' => $error));
      }
    }
    return $value;
  }

  public function save()
  {
    Doctrine::getTable('BlogRssCache')->deleteByMemberId($this->member->getId());
    parent::save();
    Doctrine::getTable('BlogRssCache')->updateByMemberId($this->member->getId());

    return true;
  }
}

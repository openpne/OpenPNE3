<?php

require_once ('ActiveResource.php');

class opRedmineProjectResource extends ActiveResource
{
  public function __construct($data = array())
  {
    parent::__construct($data);

    $this->site = opPluginChannelServerToolkit::getConfig('related_redmine_base_url', 'http://redmine.openpne.jp/');
    $this->request_format = 'xml';
    $this->element_name = 'projects';
  }

  public function _xml_entities($string)
  {
    // skip converting
    return $string;
  }
}

class opRedmineMemberResource extends ActiveResource
{
  public function __construct($data = array())
  {
    parent::__construct($data);

    $this->site = opPluginChannelServerToolkit::getConfig('related_redmine_base_url', 'http://redmine.openpne.jp/');
    $this->request_format = 'xml';
    $this->element_name = 'members';
  }

  public function _xml_entities($string)
  {
    // skip converting
    return $string;
  }
}

class opRedmineUserResource extends ActiveResource
{
  public function __construct($data = array())
  {
    parent::__construct($data);

    $this->site = opPluginChannelServerToolkit::getConfig('related_redmine_base_url', 'http://redmine.openpne.jp/');
    $this->request_format = 'xml';
    $this->element_name = 'users';
  }

  public function _xml_entities($string)
  {
    // skip converting
    return $string;
  }
}

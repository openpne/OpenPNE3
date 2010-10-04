<?php

class opTemplateRendererTwig extends sfTemplateRendererTwig
{
  public function __construct(Twig_Loader $loader = null, Twig_Environment $environment = null)
  {
    parent::__construct($loader, $environment);
    $this->environment->addExtension(new opTwigCoreExtension());

    if (sfConfig::get('op_is_restrict_mail_template', true))
    {
      $policy = new opTwigSandboxSecurityPolicy();
      $this->environment->addExtension(new Twig_Extension_Sandbox($policy, true));
    }

    $this->environment->addExtension(new HelperTwigExtension());
  }

  public function evaluate(sfTemplateStorage $template, array $parameters = array())
  {
    if (sfConfig::get('op_is_restrict_mail_template', true))
    {
      $parameters = $this->filterParameters($parameters);
    }

    return parent::evaluate($template, $parameters);
  }

  protected function filterParameters($parameters)
  {
    $filtered = array_map(array($this, 'normalizeParametersCallback'), $parameters);
    $filtered = $this->filterIgoredParameters($filtered);

    return $filtered;
  }

  protected function normalizeParametersCallback($current)
  {
    if ($current instanceof sfOutputEscaper)
    {
      $current = $current->getRawValue();
    }

    if ($current instanceof Member)
    {
      $member = $current->toArray();
      $member['profile'] = array();
      foreach ($current->getProfiles() as $v)
      {
        $member['profile'][$v->name] = $v->getValue();
      }

      // for BC
      $member['getRawValue'] = new opTwigDummyMemberProfile($member['id']);

      $member['config'] = array();
      foreach ($current->getMemberConfig() as $v)
      {
        $member['config'][$v->name] = $v->getValue();
      }

      return array_map(array($this, 'normalizeParametersCallback'), $member);
    }

    if ($current instanceof Gadget)
    {
      return $current;
    }

    if ($current instanceof Doctrine_Record)
    {
      return array_map(array($this, 'normalizeParametersCallback'), $current->toArray());
    }

    if (is_array($current))
    {
      return array_map(array($this, 'normalizeParametersCallback'), $current);
    }

    return $current;
  }

  protected function filterIgoredParameters($current)
  {
    foreach ($current as $k => $v)
    {
      if (is_array($v))
      {
        $v = $this->filterParameters($v);
        $current[$k] = $v;
      }
    }

    return array_filter($current, array($this, 'filterIgoredParametersCallback'));
  }

  protected function filterIgoredParametersCallback($current)
  {
    $allowedClasses = array('opConfig', 'opColorConfig', 'SnsTermTable', 'Gadget');

    if (is_scalar($current) || is_array($current) || in_array(get_class($current), $allowedClasses) || empty($current))
    {
      return true;
    }

    return false;
  }
}

class opTwigDummyMemberProfile
{
  protected $id;

  public function __construct($id)
  {
    $this->id = $id;
  }

  public function getProfile($name)
  {
    $member = Doctrine::getTable('Member')->find($this->id);

    return (string)$member->getProfile($name);
  }
}

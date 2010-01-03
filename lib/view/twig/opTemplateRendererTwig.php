<?php

class opTemplateRendererTwig extends sfTemplateRendererTwig
{
  public function __construct(Twig_Loader $loader = null, Twig_Environment $environment = null)
  {
    parent::__construct($loader, $environment);
    $this->environment->addExtension(new HelperTwigExtension());
  }

  public function evaluate(sfTemplateStorage $template, array $parameters = array())
  {
    if (sfConfig::get('op_is_restrict_mail_template', true))
    {
      $parameters = new opFilteredParameter($parameters);
    }

    return parent::evaluate($template, $parameters);
  }
}

class opFilterTemplateParameterIterator extends FilterIterator
{
  protected $allowedClasses = array('opConfig', 'opColorConfig', 'SnsTermTable');

  public function accept()
  {
    $current = $this->current();
    if ($current instanceof sfOutputEscaper)
    {
      $current = $this->current()->getRawValue();
    }

    if ($current instanceof Member)
    {
      $member = $current->toArray();
      $member['profile'] = array();
      foreach ($current->getProfiles() as $v)
      {
        $member['profile'][$v->name] = $v->getValue();
      }

      $member['config'] = array();
      foreach ($current->getMemberConfig() as $v)
      {
        $member['config'][$v->name] = $v->getValue();
      }

      $this->offsetSet($this->key(), $member);
    }
    elseif ($current instanceof Doctrine_Record)
    {
      $this->offsetSet($this->key(), $current->toArray());
    }
    elseif (empty($current))
    {
      return true;
    }
    elseif (is_scalar($current) || is_array($current) || in_array(get_class($current), $this->allowedClasses))
    {
      $this->offsetSet($this->key(), $current);
    }
    else
    {
      return false;
    }

    return true;
  }
}

class opFilteredParameter extends ArrayObject
{
  protected $filter = null;

  public function __construct($params)
  {
    $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($params), RecursiveIteratorIterator::SELF_FIRST);
    $this->filter = new opFilterTemplateParameterIterator($iterator);

    parent::__construct($this->doFilter());
  }

  public function doFilter()
  {
    $data = array();
    $pos = array();
    foreach ($this->filter as $k => $v)
    {
      $depth = $this->filter->getInnerIterator()->getDepth();
      $pos[$depth] = $k;

      $_current =& $data;
      for ($i = 0; $i < $depth; $i++)
      {
        $_current =& $_current[$pos[$i]];
      }

      $_current[$k] = $this->filter->offsetGet($k);
    }

    return $data;
  }
}

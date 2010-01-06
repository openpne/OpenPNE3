<?php

class HelperTwigExtension extends Twig_Extension
{
  static protected $helpers = array();

  public function getTokenParsers()
  {
    if (empty(self::$helpers))
    {
      self::$helpers = $this->generateHelperList();
    }

    $parsers = array();
    foreach (self::$helpers as $helper)
    {
      $parsers[] = new HelperTokenParser($helper);
    }

    return $parsers;
  }

  public function generateHelperList()
  {
    $result = array();

    $funcs = get_defined_functions();
    foreach ($funcs['user'] as $func)
    {
      $reflection = new ReflectionFunction($func);
      if (strpos($reflection->getFileName(), 'helper'))
      {
        $result[] = $func;
      }
    }

    return $result;
  }

  public function getName()
  {
    return 'helper';
  }
}

class HelperTokenParser extends Twig_TokenParser
{
  protected $name = '';

  public function __construct($name)
  {
    $this->name = $name;
  }

  public function parse(Twig_Token $token)
  {
    $arguments = $this->parser->getExpressionParser()->parseArguments();
    $this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

    return new HelperNode($this->getTag(), $arguments);
  }

  public function getTag()
  {
    return $this->name;
  }
}

class HelperNode extends Twig_Node
{
  protected $name, $arguments;

  public function __construct($name, $arguments)
  {
    $this->name = $name;
    $this->arguments = $arguments;
  }

  public function compile($compiler)
  {
    $arguments = array();
    foreach ($this->arguments as $k => $argument)
    {
      $arguments[$k] = '$_v'.$k;
      $compiler->write('$_v'.$k.' = ');
      $argument->compile($compiler);
      $compiler->write(';');
    }

    $compiler->write('echo '.$this->name.'('.implode(', ', $arguments).');');
  }
}


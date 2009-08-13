<? // short open tag

# shell script style comment
//C++ style comment doesn't start with space

// brace is written the same line with the function name
function globalScopeAndNonUnderscoredFunction {
$indentation = 'non indentation';
 $indentation = '1 space indentation';
   $indentation = '3 space indentation';
    $indentation = '4 space indentation';

	$indentation = 'tabs';

    $lineLength = 'Toooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo long line (warning).';
    $lineLength = 'Toooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo long line (error).';

    $lineTermination = 'CRLF';
    $lineTermination = 'CR';$thisIsNextLine = 'foo';
}

define('GLOBAL_LEVEL_CONSTANT', 'This is not a valid.');

// brace is written the same line with the class name
// and it doesn't have a PHPDocumentor formatted comment
class non_prefixed_and_non_camel_case_class {

  public $non_camel_case_property = 'this is not a valid';
  $nonAccessLevelKeywordProperty = 'this is not a valid';

  // brace is written the same line with the method name
  public function non_camel_case_method {
  }

  function nonAccessLevelKeywordMethod()
  {
  }

  public function functionNameEndWithSpace ()
  {
    // arguments start and end with space
    $this->nonAccessLevelKeywordMethod( 'arguments','do','not','have','space' );
  }

  public function returnDoesNotHaveABlankLine()
  {
    $isValid = false;
    return $isValid;
  }


}

interface opThisInterfaceIsNotSuffixed
{
}

$literalStringIsConstructedByDoubleQuote = "This is not a valid.";
$literalStringIsConstructedByDoubleQuote = "This is a 'valid'.";

$variableToSubstitute = 'not a valid';
$variableSubstitutionInString = "This is '{$variableToSubstitute}'.";
$variableSubstitutionInString = "This is '$variableToSubstitute'.";
$variableSubstitutionInString = "This is '${variableToSubstitute}'.";

$variableToConcatenate = 'a valid';
$variableConcatenation = 'This is '.$variableToConcatenate;
$variableConcatenation = sprintf('This is %s', $variableToConcatenate);

$stringConcatenationWithSpace = 'This' . 'is' . 'not' . 'a' . 'valid';
$stringConcatenationWithWrongIndentations = 'This'
  .'is'
    .'not'
      .'a'
        .'valid'
          .'even'
            .'it'
              .'is'
                .'constructed'
                  .'by'
                    .'2'
                      .'spaces'
                        .'because'
                          .'it'
                            .'does'
                              .'not'
                                .'begin'
                                  .'under'
                                    .'the'
                                      .'='
                                        .'operator'
                                          .'Do'
                                            .'you'
                                              .'understand'
                                                .'about'
                                                  .'it'
                                                    .'?';

$stringConcatenationWithValidIndentations = 'This'
                                          .'is'
                                          .'a'
                                          .'valid';

$arrayUsingNegativeNumber = array(-10 => 'this is not a valid');
$validNumericallyIndexedArray = array('this', 'is', 'a', 'valid');
$validNumericallyIndexedArray = array(1 => 'this is a valid');

$multiLineArrayWithWrongIndentations =
array(  // new line definition
       'this', 'is', 'not'
         'a', 'valid' // the last item doesn't have ,
);

$multiLineArrayClosingParenDoesNotHaveOwnLine = array(
  'this', 'is', 'not',
  'a', 'valid',);

$goodMultiLineArray = array(
  'this', 'is',
  'a',
  'good',
  'multi', 'line',
  'array',
);

$oneLineAssociativeArray = array('this' => 'is', 'not' => 'a', 'valid' => '!');
$multiLineArrayWithWrongIndentations =
array(  // new line definition
       'this'  => 'is',
       'not'   => 'a',
       'valid' => '!' // the last item doesn't have ,
);

$multiLineArrayClosingParenDoesNotHaveOwnLine = array(
  'this' => 'is',
  'not'  => 'a',
  'valid' => '!',);

$betterAssociativeArray = array(
  'this' => 'is',
  'a' => 'better',
);

$bestAssociativeArray = array(
  'this' => 'is',
  'a'    => 'best',
);

$foo = 1;
if (1 == $foo) { // brace is the same line
echo 'This is a wrong indentation';
 echo 'This is a wrong indentation';
   echo 'This is a wrong indentation';
    echo 'This is a wrong indentation';
  echo 'This is a good indentation';
}
elseif(2 == $foo) // parenthesis doesn't have space
{
echo $foo;} // brace is the same line

try {
  $isValid = false;
} catch (Exception $e) {
  // nothing to do
}

do {
  echo 'bad';
} while (false);

while (false) {
 echo 'bad';
}

for ($i = 0; $i > 10; $i++) {
  echo 'bad';
}

$list = array('bad', 'bad');
foreach ($list as $value)
{
  echo $value;
}

$shouldUseIsNullFunction = (null === $foo);
$shouldPutTheValueFirst = ($foo == 1);

/**
 * This is not a doc block format
 */
class opThisIsNotAValidClass
{
}

/**
 * Short description doesn't have a blank line.
 * @package OpenPNE
 * @author  Your name here
 */
class opThisIsNotAValidClass
{
}

/**
 * Author is a default.
 *
 * @package    symfony
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 */
class opThisIsNotAValidClass
{
}

/**
 * It doesn't have author.
 *
 * @package OpenPNE
 */
class opThisIsNotAValidClass
{
}

/**
 * It doesn't have package.
 *
 * @author Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opThisIsNotAValidClass
{
}

/**
 * Good class.
 *
 * @package OpenPNE
 * @author  Kousuke Ebihara <ebiahra@tejimaya.com>
 */
class opThisIsAValidClass
{
}

/**
 * Good class too.
 *
 * @package opExamplePlugin
 * @author  Kousuke Ebihara <ebiahra@tejimaya.com>
 */
class opThisIsAValidClass
{
}

// close tag
?>

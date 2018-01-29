<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

/**
 * Implementation of the os-template / os-data expression language, which is based on the JSP EL syntax
 * For reference on the language see:
 * JSP EL: https://jsp.dev.java.net/spec/jsp-2_1-fr-spec-el.pdf
 * OS Templates: http://opensocial-resources.googlecode.com/svn/spec/0.9/OpenSocial-Templating.xml
 * OS Data pipelining: http://opensocial-resources.googlecode.com/svn/spec/0.9/OpenSocial-Data-Pipelining.xml
 *
 */

//TODO == seems to not be working correctly
//TODO support unary expressions, ie: ${Foo * -Bar}, or simpler: ${!Foo}

class ExpressionException extends Exception {
}

class ExpressionParser {

  private static $variablePrecedence = array(false, 'Cur', 'My', 'Top');
  private static $singleOperators = array('+', '-', '/', '^', '%', '!', '<', '>', '*', '=', '&', '|');
  private static $doubleOperators = array('lt', 'gt', 'le', 'ge', '==', '!=', 'eq', 'ne', 'or', '>=', '<=', '||', '&&');
  private static $trippleOperators = array('and', 'not', 'div', 'mod');
  private static $quadrupleOperators = array('empty');
  private static $reservedWords = array('and', 'eq', 'gt', 'true', 'instanceof', 'or', 'ne', 'le', 'false', 'empty', 'not', 'lt', 'ge', 'null', 'div', 'mod');

  private static $dataContext;

  /**
   * Evaluates the given $expression using the $dataContext as variable source.
   *
   * Internally the ExpressionParser uses a infix to postfix conversion to easily
   * be able to evaluate mathematical expressions
   *
   * @param string $expression
   * @param array $dataContext
   * @return string evaluated result or an exception of failure
   */
  static public function evaluate($expression, $dataContext) {
    self::$dataContext = $dataContext;
    if (strpos($expression, '?') !== false) {
      // Quick and dirty support for Ternary operation ie: color="${ViewerData.likesRed ? 'Red' : 'Blue'}"
      $parts = self::splitTernaryOperation($expression);
      $condition = self::evaluate($parts[0], $dataContext);
      if ($condition) {
        return self::evaluate($parts[1], $dataContext);
      } else {
        return self::evaluate($parts[2], $dataContext);
      }
    } else {
      // plain old single or binary expression, just evaluate it
      $postfix = self::infixToPostfix($expression);
      $result = self::postfixEval($postfix);
    }
    return $result;
  }

  /**
   * Evaluates a variable name (Foo.Bar.id style) agains the data context and returns the value, or throws an exception if not found
   *
   * @param string $var variable name
   * @param array $dataContext the data context to use
   * @return mixed value
   */
  static public function evaluateVar($var, $dataContext) {
    if (empty($var)) {
      throw new ExpressionException("Invalid variable statement");
    }
    if (strtolower($var) == 'null') {
      return null;
    }
    if (strtolower($var) == 'false') {
      return false;
    }
    if (strtolower($var) == 'true') {
      return true;
    }
    if ($var === null || $var === false) {
      return $var;
    }
    if ($var[0] == '"' || $var[0] == "'") {
      return substr($var, 1, strlen($var) - 2);
    }
    if (in_array($var, self::$reservedWords)) {
      throw new ExpressionException("Variable name  " . htmlentities($var) . " is reserved word");
    }
    $parts = explode('.', $var);
    if (count($parts) < 1) {
      throw new ExpressionException("Invalid variable statement");
    }
    // Check the scope in order of precedence: ${Cur}, ${My} and then ${Top} (where top is the global variable context)
    foreach (self::$variablePrecedence as $variableType) {
      $found = true;
      $context = $variableType ? $dataContext[$variableType] : $dataContext;
      foreach ($parts as $key) {
        //TODO check to see what the right resolving behavior is. If Cur.Foo = 'string' and Top.Foo = array, should we skip the Cur one and resolve to the top variable, or throw an error?
        if (! is_array($context)) {
          throw new ExpressionException("Variable is not an array (" . htmlentities($key) . ")");
        } elseif (! isset($context[$key])) {
          // progress to the next variable precendence scope
          $found = false;
          break;
        }
        $context = $context[$key];
      }
      if ($found) {
        break;
      }
    }
    if (! $found) {
      // variable wasn't found in Cur, My and Top scope, throw an error
      throw new ExpressionException("Unknown variable: " . htmlentities($var) . ($var != $key ? " ($key)" : ''));
    }
    return $context;
  }

  /**
   * Internal misc function that splits a Ternary operation into it's 3 parts, so given:
   * ${ViewerParams.likesRed ? 'Red' : 'Blue'} it will return:
   * array('0' => 'ViewerParams.likesRed', '1' => 'Red', '2' => 'Blue')
   *
   * @param string $expression
   */
  static private function splitTernaryOperation($expression) {
    $result = array();
    $pos = strpos($expression, '?');
    $result[0] = substr($expression, 0, $pos - 1);
    $expression = substr($expression, $pos + 1);
    // Nesting detection is used so that ${Cur.id == 1 ? Cur.id == 2 ? 12 : 1  : 2} parses into it's correct components too, ie
    // that expression would result in: Array ( [0] => Cur.id == 1 [1] => Cur.id == 2 ? 12 : 1 [2] => 2 ), which will case self::evaluate()
    // to recurse on the the array element that contains a nested ternary expression
    $nestCounter = 0;
    for ($i = 0; $i < strlen($expression); $i ++) {
      $char = $expression[$i];
      if ($char == '?') {
        $nestCounter ++;
      } elseif ($char == ':') {
        if ($nestCounter == 0) {
          $result[1] = trim(substr($expression, 0, $i - 1));
          $result[2] = trim(substr($expression, $i + 1));
        } else {
          $nestCounter --;
        }
      }
    }
    if (count($result) != 3) {
      // if count != 3, the left and right parts of the ternary operation were never detected, ie an unbalanced or uncompleted expression
      throw new ExpressionException("Unbalanced ternary operation");
    }
    return $result;
  }

  /**
   * Misc function to convert an array to string, the reason a plain implode() doesn't
   * always work is because it'll complain about array to string conversions if
   * the array contains array's as entries
   *
   * @param $array
   * @return string
   */
  static private function arrayToString($array) {
    foreach ($array as $key => $entry) {
      if (is_array($entry)) {
        $array[$key] = self::arrayToString($entry);
      }
    }
    return implode(',', $array);
  }

  /**
   * Returns the string value of the (mixed) $val, ie:
   * on array, return "1, 2, 3, 4"
   * on int, return "1"
   * on string, return as is
   */
  static public function stringValue($val) {
    if (@is_array($val)) {
      return self::arrayToString($val);
    } elseif (is_numeric($val)) {
      return (string)$val;
    } else {
      return $val;
    }
  }

  static private function isOperand($string, $index = 0) {
    if (is_array($string)) {
      // complex types are always operands
      return true;
    } elseif (!isset($string[$index])) {
      return false;
    } else {
      return ((! self::isOperator($string, $index) && ($string[$index] != "(") && ($string[$index] != ")")) ? true : false);
    }
  }

  static private function isOperator($var, $index = 0) {
    // if $var is a complex type (ie something like a $ViewerFriends array), it can't be an operator
    if (is_array($var)) {
      return 0;
    }
    // parsing in reverse order of operator length so '!=' doesn't register as '!'.
    $ret = 0;
    $operator = substr($var, $index, 4);
    if (in_array($operator, self::$quadrupleOperators)) {
      $ret = 4;
    } else {
      $operator = substr($var, $index, 3);
      if (in_array($operator, self::$trippleOperators)) {
        $ret = 3;
      } else {
        $operator = substr($var, $index, 2);
        if (in_array($operator, self::$doubleOperators)) {
          $ret = 2;
        } else {
          $operator = substr($var, $index, 1);
          if (in_array($operator, self::$singleOperators)) {
            $ret = 1;
          }
        }
      }
    }
    if ($ret && in_array($operator, self::$reservedWords)) {
      // if this is a reserved word, it's a string, so make sure the next char isn't a continuation of a string, otherwise this would
      // end up thinking that 'need' is the operator 'ne' and the operand 'ed'
      if (strlen($var) < ($index + $ret + 1)) {
        return $ret;
      } else {
        $char = substr($var, $index + $ret, 1);
        if ($char != ' ' && $char != "\n" && $char != "\t" && $char != "\r" && ! in_array($char, self::$singleOperators)) {
          // if the 'next char' not a blank and in the singleOperators array (+-/!=^*<>&|) this was a false match
          return 0;
        }
      }
    }
    return $ret;
  }

  static private function top($stack) {
    return isset($stack[count($stack) - 1]) ? ($stack[count($stack) - 1]) : null;
  }

  static private function precedence($operator) {
    // JSP EL operator precedences are defined in section 1.13 of it's spec, the deviation on the ( and ) priorities
    // is due to the way infix to postix processing works.
    if ($operator == '*' || $operator == '/' || $operator == 'div' || $operator == 'mod' || $operator == '%') {
      return (8);
    } elseif ($operator == '+' || $operator == '-') {
      return (7);
    } elseif ($operator == 'lt' || $operator == 'gt' || $operator == 'le' || $operator == 'ge' || $operator == '<' || $operator == '>' || $operator == '>=' || $operator == '<=') {
      return (6);
    } elseif ($operator == '==' || $operator == '!=' || $operator == 'eq' || $operator == 'ne') {
      return (5);
    } elseif ($operator == 'and' || $operator == '&&') {
      return (4);
    } elseif ($operator == 'or' || $operator == '||') {
      return (3);
    } elseif ($operator == '(') {
      return (2);
    } elseif ($operator == ')') {
      return (1);
    } else {
      return 0;
    }
  }

  static private function infixToPostfix($infix) {
    $postfix = array();
    $stack = array();
    $postfixPtr = 0;
    $infix = self::strToTokens($infix);
    for ($i = 0; $i < count($infix); $i ++) {
      if (self::isOperand($infix[$i])) {
        $postfix[$postfixPtr] = $infix[$i];
        $postfixPtr ++;
      }
      if (($opLen = self::isOperator($infix[$i])) !== 0) {
        if ($opLen > 1) {
          $i += $opLen - 1;
        }
        $operator = substr($infix[$i], 0, $opLen);
        while ((! empty($stack)) && (self::precedence($operator) <= self::precedence(self::top($stack)))) {
          $postfix[$postfixPtr] = self::top($stack);
          array_pop($stack);
          $postfixPtr ++;
        }
        array_push($stack, $operator);
      }
      if ($infix[$i] == "(") {
        array_push($stack, $infix[$i]);
      }
      if ($infix[$i] == ")") {
        while (self::top($stack) != "(") {
          $postfix[$postfixPtr] = array_pop($stack);
          $postfixPtr ++;
        }
        array_pop($stack);
      }
    }
    while (! empty($stack)) {
      if (self::top($stack) == "(") {
        array_pop($stack);
      } else {
        $postfix[count($postfix)] = array_pop($stack);
      }
    }
    return ($postfix);
  }

  static private function strToTokens($str) {
    $temp = $token = '';
    $tokens = array();
    $tokensIndex = 0;
    for ($i = 0; $i < strlen($str); $i ++) {
      // Resolve array index selections[] by calling self::evaluate on the nested index key, and using the return value of that to resolve the var
      // Note: this will recurse when there's multiple levels[of[brackets]]
      if ($str[$i] == '[') {
        if (! empty($temp)) {
          $tokens[$tokensIndex] = $temp;
          $tokensIndex ++;
        }
        $tokenOperand = array_pop($tokens);
        if (! self::isOperand($tokenOperand)) {
          throw new ExpressionException("Trying to reference an index on an operator");
        }
        $tokenOperand = self::evaluateVar($tokenOperand, self::$dataContext);
        if (! is_array($tokenOperand)) {
          throw new ExpressionException("Trying to reference an index on a non-array value");
        }
        $resolved = false;
        $temp = '';
        $nestCounter = $expressionLength = 0;
        $indexExpression = '';
        for ($y = $i + 1; $y < strlen($str); $y ++) {
          $expressionLength ++;
          $char = $str[$y];
          if ($char == '[') {
            $nestCounter ++;
          } elseif ($char == ']') {
            if ($nestCounter == 0) {
              $indexResult = self::evaluate($indexExpression, self::$dataContext);
              if (! isset($tokenOperand[$indexResult])) {
                $resolved = null;
              } else {
                $resolved = $tokenOperand[$indexResult];
              }
              break;
            } else {
              $nestCounter --;
            }
          }
          $indexExpression .= $char;
        }
        if ($resolved === false) {
          throw new ExpressionException("Unbalanced [] expression");
        }
        $i += $expressionLength + 1;
        if ($resolved === null && $i < (strlen($str) - 1)) {
          // a null value is ok for a Foo[Bar] expression, but an "property not found" exception on Foo[Bar].id
          if ($str[$i] != ' ' && ! self::isOperator($str, $i)) {
            throw new ExpressionException("Trying to get a property on a null value");
          }
        }
        // this token has been resolved, advance our string pointer ($i) and continue to the next position in the string
        $tokens[$tokensIndex - 1] = $resolved;
        continue;
      }

      if ($str[$i] == '"' || $str[$i] == "'") {
        // properly parse strings as one token so that operators and spaces aren't seen as seperators
        if (! empty($temp)) {
          $tokens[$tokensIndex] = $temp;
          $tokensIndex ++;
        }
        $quote = $str[$i];
        $foundEndQuote = false;
        $string = $quote;
        for ($y = $i + 1; $y < strlen($str) ; $y++) {
          $i ++;
          $string .= $str[$y];
          if ($str[$y] == $quote) {
            if ($y > 1 && $str[$y - 1] != '\\') {
              // + and previous char wasn't a \
              $foundEndQuote = true;
              break;
            }
          }
        }
        if (!$foundEndQuote) {
          throw new ExpressionException("Unterminated string");
        }
        $tokens[$tokensIndex] = $string;
        $tokensIndex ++;
        continue;
      }

      // Regular operant/operator token
      if ($str[$i] == ' ' || $str[$i] == "\t" || $str[$i] == "\n" || $str == "\r") {
        if (! empty($temp)) {
          $tokens[$tokensIndex] = $temp;
          $tokensIndex ++;
        }
        $temp = '';
        continue;
      }
      if (!empty($temp) || self::isOperand($str, $i)) {
        $temp = $temp . $str[$i];
      }
      $tokenLen = empty($temp) ? self::isOperator($str, $i) : false;
      if ($tokenLen || $str[$i] == ")" || $str[$i] == "(") {
        $token = substr($str, $i, $tokenLen);
        if ($tokenLen > 1) {
          $i += ($tokenLen - 1);
        }
        if (! empty($temp)) {
          $tokens[$tokensIndex] = $temp;
          $tokensIndex ++;
        }
        $temp = '';
        $tokens[$tokensIndex] = $token;
        $tokensIndex ++;
      }
      if ($i == strlen($str) - 1) {
        if (! empty($temp)) {
          $tokens[$tokensIndex] = $temp;
        }
      }
    }
    // Resolve all named variables to their actual value
    foreach ($tokens as $key => $val) {
      if (self::isOperand($val) && ! is_numeric($val) && $val !== false && $val !== null) {
        $tokens[$key] = self::evaluateVar($val, self::$dataContext);
      }
    }
    return $tokens;
  }

  static private function compute($var1, $var2, $sym) {
    $returnVal = 0;
    // JSP EL doesn't allow $A = 'foo'; $b = 1; $C = $A + $B, so make sure that both variables are numberic when doing arithmatic or boolean operations
    if ($sym != 'empty' && (! is_numeric($var1) || ! is_numeric($var2))) {
      throw new ExpressionException("Can't perform arithmatic or boolean operation (" . htmlentities($sym) . ") on non numeric values: " . htmlentities($var1) . ", " . htmlentities($var2) . ")");
    }
    //TODO variable type coercion in JSP EL is different from PHP, it might be prudent to code out the same behavior
    switch ($sym) {
      // Unhandled operators
      case '=':
        throw new ExpressionException("Invalid operator (=)");
        break;
      case '&':
        throw new ExpressionException("Invalid operator (&)");
        break;

      // Arithmatic operators
      case '+':
        $returnVal = $var1 + $var2;
        break;

      case '-':
        $returnVal = $var1 - $var2;
        break;

      case '*':
        $returnVal = $var1 * $var2;
        break;

      case '/':
      case 'div':
        $returnVal = $var1 / $var2;
        break;

      case '%':
      case 'mod':
        // % in PHP returns the integer remainder, while fmod returns the float remainder.. The JSP EL spec states
        // that if one of the 2 vars is a float, a float should be returned, otherwise the int val
        if (is_float($var1) || is_float($var2)) {
          $returnVal = fmod($var1, $var2);
        } else {
          $returnVal = $var1 % $var2;
        }
        break;

      // Boolean operators
      case '<':
      case 'lt':
        $returnVal = $var1 < $var2;
        break;

      case '>':
      case 'gt':
        $returnVal = $var1 > $var2;
        break;

      case '<=':
      case 'le':
        $returnVal = $var1 <= $var2;
        break;

      case '>=':
      case 'ge':
        $returnVal = $var1 >= $var2;
        break;

      case '!=':
      case 'ne':
        $returnVal = $var1 != $var2;
        break;

      case '==':
      case 'eq':
        $returnVal = $var1 == $var2;
        break;

      case '||':
      case 'or':
        $returnVal = $var1 || $var2;
        break;

      case '&&':
      case 'and':
        $returnVal = $var1 && $var2;
        break;

      case '!':
      case 'not':
        $returnVal = ! $var1;
        break;

      case 'empty':
        $returnVal = empty($var1);
        break;
    }
    return $returnVal;
  }

  static private function postfixEval($postfix) {
    $stack = array();
    for ($i = 0; $i < count($postfix); $i ++) {
      if (self::isOperand($postfix[$i])) array_push($stack, $postfix[$i]);
      else {
        $temp = self::top($stack);
        array_pop($stack);
        $val = self::compute(self::top($stack), $temp, $postfix[$i]);
        array_pop($stack);
        array_push($stack, $val);
      }
    }
    return self::top($stack);
  }
}

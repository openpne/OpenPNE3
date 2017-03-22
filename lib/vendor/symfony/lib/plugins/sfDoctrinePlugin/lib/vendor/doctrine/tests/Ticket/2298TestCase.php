<?php

class Doctrine_Ticket_2298_TestCase extends Doctrine_UnitTestCase 
{
    public function testTokenizerIgnoresQuotes()
    {
        $q = Doctrine_Query::create()
            ->from('Address a')
            ->where("a.address = '(a) and c'");
        $this->assertEqual($q->getSqlQuery(), "SELECT a.id AS a__id, a.address AS a__address FROM address a WHERE (a.address = '(a) and c')");

        $q = Doctrine_Query::create()
            ->from('Address a')
            ->where("a.address = ' or what'");
        $this->assertEqual($q->getSqlQuery(), "SELECT a.id AS a__id, a.address AS a__address FROM address a WHERE (a.address = ' or what')");

        $q = Doctrine_Query::create()
            ->from('Address a')
            ->where("a.address = ' or      6spaces'");
       	$this->assertEqual($q->getSqlQuery(), "SELECT a.id AS a__id, a.address AS a__address FROM address a WHERE (a.address = ' or      6spaces')");
    }


	public function testEscapedQuotes()
    {
        $tokenizer = new Doctrine_Query_Tokenizer();
        $delimiters = array(' ', '+', '-', '*', '/', '<', '>', '=', '>=', '<=', '&', '|');

        $res = $tokenizer->bracketExplode("'a string with AND in the middle'", ' AND ');
        $this->assertEqual($res, array("'a string with AND in the middle'"));

        $res = $tokenizer->bracketExplode("'o\' AND string'", ' AND ');
        $this->assertEqual($res, array("'o\' AND string'"));

        $res = $tokenizer->sqlExplode("('John O\'Connor (West) as name'+' ') + 'b'", $delimiters);
        $this->assertEqual($res, array("('John O\'Connor (West) as name'+' ')", '', '', "'b'"));

        $res = $tokenizer->sqlExplode("'(Word) and' term", $delimiters);
        $this->assertEqual($res, array("'(Word) and'", 'term'));
    }


	public function testAdditionalTokenizerFeatures()
    {
    	// These tests all pass with the old tokenizer, they were developed wile
    	// working on the patch
    	$tokenizer = new Doctrine_Query_Tokenizer();
    	$delimiters = array(' ', '+', '-', '*', '/', '<', '>', '=', '>=', '<=', '&', '|');

    	$res = $tokenizer->bracketExplode("(age < 20 AND age > 18) AND email LIKE 'John@example.com'", ' AND ', '(', ')');
        $this->assertEqual($res, array("(age < 20 AND age > 18)","email LIKE 'John@example.com'"));

    	$res = $tokenizer->sqlExplode("sentence OR 'term'", ' OR ');
        $this->assertEqual($res, array("sentence", "'term'"));

        $res = $tokenizer->clauseExplode("'a + b'+c", $delimiters);
        $this->assertEqual($res, array(array("'a + b'",'+'), array('c', '')));

        $res = $tokenizer->quoteExplode('"a"."b"', ' ');
        $this->assertEqual($res, array('"a"."b"'));
    }
}

?>
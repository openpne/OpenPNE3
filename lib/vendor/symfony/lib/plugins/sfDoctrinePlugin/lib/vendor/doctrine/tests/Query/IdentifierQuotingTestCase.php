<?php
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Doctrine_Query_IdentifierQuoting_TestCase
 *
 * This test case is used for testing DQL API quotes all identifiers properly
 * if idenfitier quoting is turned on
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Query_IdentifierQuoting_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables() 
    { 
        $this->tables = array('Entity', 'Phonenumber');
        
        parent::prepareTables();
    }

    public function prepareData()
    { }

    public function testQuerySupportsIdentifierQuoting() 
    {
        $this->conn->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);

        $q = new Doctrine_Query();

        $q->parseDqlQuery('SELECT u.id, MAX(u.id), MIN(u.name) FROM User u');

        $this->assertEqual($q->getSqlQuery(), 'SELECT "e"."id" AS "e__id", MAX("e"."id") AS "e__0", MIN("e"."name") AS "e__1" FROM "entity" "e" WHERE ("e"."type" = 0)');

        $q->execute();
    }

    public function testQuerySupportsIdentifierQuotingInWherePart()
    {
        $q = new Doctrine_Query();

        $q->parseDqlQuery('SELECT u.name FROM User u WHERE u.id = 3');

        $this->assertEqual($q->getSqlQuery(), 'SELECT "e"."id" AS "e__id", "e"."name" AS "e__name" FROM "entity" "e" WHERE ("e"."id" = 3 AND ("e"."type" = 0))');
    
        $q->execute();
    }

    /*
    public function testQuerySupportsIdentifierQuotingWorksWithinFunctions()
    {
        $q = new Doctrine_Query();

        $q->parseDqlQuery("SELECT u.name FROM User u WHERE TRIM(u.name) = 'zYne'");

        $this->assertEqual($q->getSqlQuery(), 'SELECT "e"."id" AS "e__id", "e"."name" AS "e__name" FROM "entity" "e" WHERE TRIM(u.name) = 3 AND ("e"."type" = 0)');
    }
    */

    public function testQuerySupportsIdentifierQuotingWithJoins() 
    {
        $q = new Doctrine_Query();

        $q->parseDqlQuery('SELECT u.name FROM User u LEFT JOIN u.Phonenumber p');

        $this->assertEqual($q->getSqlQuery(), 'SELECT "e"."id" AS "e__id", "e"."name" AS "e__name" FROM "entity" "e" LEFT JOIN "phonenumber" "p" ON "e"."id" = "p"."entity_id" WHERE ("e"."type" = 0)');

    }

    public function testLimitSubqueryAlgorithmSupportsIdentifierQuoting()
    {
        $q = new Doctrine_Query();

        $q->parseDqlQuery('SELECT u.name FROM User u INNER JOIN u.Phonenumber p')->limit(5);

        $this->assertEqual($q->getSqlQuery(), 'SELECT "e"."id" AS "e__id", "e"."name" AS "e__name" FROM "entity" "e" INNER JOIN "phonenumber" "p" ON "e"."id" = "p"."entity_id" WHERE "e"."id" IN (SELECT DISTINCT "e2"."id" FROM "entity" "e2" INNER JOIN "phonenumber" "p2" ON "e2"."id" = "p2"."entity_id" WHERE ("e2"."type" = 0) LIMIT 5) AND ("e"."type" = 0)');
    }
    
    public function testCountQuerySupportsIdentifierQuoting()
    {
        $q = new Doctrine_Query();

        $q->parseDqlQuery('SELECT u.name FROM User u INNER JOIN u.Phonenumber p');
        
        $this->assertEqual($q->getCountSqlQuery(), 'SELECT COUNT(*) AS "num_results" FROM (SELECT "e"."id" FROM "entity" "e" INNER JOIN "phonenumber" "p" ON "e"."id" = "p"."entity_id" WHERE ("e"."type" = 0) GROUP BY "e"."id") "dctrn_count_query"');
    }

    public function testUpdateQuerySupportsIdentifierQuoting()
    {
        $q = new Doctrine_Query();

        $q->parseDqlQuery('UPDATE User u SET u.name = ? WHERE u.id = ?');
        
        $this->assertEqual($q->getSqlQuery(), 'UPDATE "entity" SET "name" = ? WHERE ("id" = ? AND ("type" = 0))');
    }

    public function testUpdateQuerySupportsIdentifierQuoting2()
    {
        $q = new Doctrine_Query();

        $q->update('User')->set('name', '?', 'guilhermeblanco')->where('id = ?');
        
        $this->assertEqual($q->getSqlQuery(), 'UPDATE "entity" SET "name" = ? WHERE ("id" = ? AND ("type" = 0))');
    }

    public function testUpdateQuerySupportsIdentifierQuoting3()
    {
        $q = new Doctrine_Query();

        $q->update('User')->set('name', 'LOWERCASE(name)')->where('id = ?');
        
        $this->assertEqual($q->getSqlQuery(), 'UPDATE "entity" SET "name" = LOWERCASE("name") WHERE ("id" = ? AND ("type" = 0))');
    }

    public function testUpdateQuerySupportsIdentifierQuoting4()
    {
        $q = new Doctrine_Query();

        $q->update('User u')->set('u.name', 'LOWERCASE(u.name)')->where('u.id = ?');
        
        $this->assertEqual($q->getSqlQuery(), 'UPDATE "entity" SET "name" = LOWERCASE("name") WHERE ("id" = ? AND ("type" = 0))');
    }

    public function testUpdateQuerySupportsIdentifierQuoting5()
    {
        $q = new Doctrine_Query();

        $q->update('User u')->set('u.name', 'UPPERCASE(LOWERCASE(u.name))')->where('u.id = ?');
        
        $this->assertEqual($q->getSqlQuery(), 'UPDATE "entity" SET "name" = UPPERCASE(LOWERCASE("name")) WHERE ("id" = ? AND ("type" = 0))');
    }

    public function testUpdateQuerySupportsIdentifierQuoting6()
    {
        $q = new Doctrine_Query();

        $q->update('User u')->set('u.name', 'UPPERCASE(LOWERCASE(u.id))')->where('u.id = ?');
        
        $this->assertEqual($q->getSqlQuery(), 'UPDATE "entity" SET "name" = UPPERCASE(LOWERCASE("id")) WHERE ("id" = ? AND ("type" = 0))');
    }

    public function testUpdateQuerySupportsIdentifierQuoting7()
    {
        $q = new Doctrine_Query();

        $q->update('User u')->set('u.name', 'CURRENT_TIMESTAMP')->where('u.id = ?');
        
        $this->assertEqual($q->getSqlQuery(), 'UPDATE "entity" SET "name" = CURRENT_TIMESTAMP WHERE ("id" = ? AND ("type" = 0))');
    }

    public function testUpdateQuerySupportsIdentifierQuoting8()
    {
        $q = new Doctrine_Query();

        $q->update('User u')->set('u.id', 'u.id + 1')->where('u.name = ?');
        
        $this->assertEqual($q->getSqlQuery(), 'UPDATE "entity" SET "id" = "id" + 1 WHERE ("name" = ? AND ("type" = 0))');

        $this->conn->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, false);
    }
}

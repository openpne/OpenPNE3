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
 * Doctrine_Query_Driver_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Query_Driver_TestCase extends Doctrine_UnitTestCase
{
    public function prepareData()
    { }
    public function prepareTables()
    { }

    public function testLimitQueriesForPgsql()
    {
        $this->dbh = new Doctrine_Adapter_Mock('pgsql');

        $conn = $this->manager->openConnection($this->dbh);

        $q = new Doctrine_Query($conn);
    
        $q->from('User u')->limit(5);

        $this->assertEqual($q->getSqlQuery(), 'SELECT e.id AS e__id, e.name AS e__name, e.loginname AS e__loginname, e.password AS e__password, e.type AS e__type, e.created AS e__created, e.updated AS e__updated, e.email_id AS e__email_id FROM entity e WHERE (e.type = 0) LIMIT 5');
    }

    public function testLimitQueriesForSqlite()
    {
        $this->dbh = new Doctrine_Adapter_Mock('sqlite');

        $conn = $this->manager->openConnection($this->dbh);

        $q = new Doctrine_Query($conn);
    
        $q->from('User u')->limit(5);

        $this->assertEqual($q->getSqlQuery(), 'SELECT e.id AS e__id, e.name AS e__name, e.loginname AS e__loginname, e.password AS e__password, e.type AS e__type, e.created AS e__created, e.updated AS e__updated, e.email_id AS e__email_id FROM entity e WHERE (e.type = 0) LIMIT 5');
    }
    
    public function testLimitQueriesForMysql()
    {
        $this->dbh = new Doctrine_Adapter_Mock('mysql');

        $conn = $this->manager->openConnection($this->dbh);

        $q = new Doctrine_Query($conn);
    
        $q->from('User u')->limit(5);

        $this->assertEqual($q->getSqlQuery(), 'SELECT e.id AS e__id, e.name AS e__name, e.loginname AS e__loginname, e.password AS e__password, e.type AS e__type, e.created AS e__created, e.updated AS e__updated, e.email_id AS e__email_id FROM entity e WHERE (e.type = 0) LIMIT 5');
    }

    public function testLimitQueriesForOracle()
    {
        $this->dbh = new Doctrine_Adapter_Mock('oracle');

        $conn = $this->manager->openConnection($this->dbh);

        $q = new Doctrine_Query($conn);

        $q->from('User u')->limit(5);

        $this->assertEqual($q->getSqlQuery(), 'SELECT a.* FROM ( SELECT e.id AS e__id, e.name AS e__name, e.loginname AS e__loginname, e.password AS e__password, e.type AS e__type, e.created AS e__created, e.updated AS e__updated, e.email_id AS e__email_id FROM entity e WHERE (e.type = 0) ) a WHERE ROWNUM <= 5');
    }

    public function testLimitOffsetQueriesForOracle()
    {
        $this->dbh = new Doctrine_Adapter_Mock('oracle');

        $conn = $this->manager->openConnection($this->dbh);

        $q = new Doctrine_Query($conn);

        $q->from('User u')->limit(5)->offset(2);

        $this->assertEqual($q->getSqlQuery(), 'SELECT b.* FROM ( SELECT a.*, ROWNUM AS doctrine_rownum FROM ( SELECT e.id AS e__id, e.name AS e__name, e.loginname AS e__loginname, e.password AS e__password, e.type AS e__type, e.created AS e__created, e.updated AS e__updated, e.email_id AS e__email_id FROM entity e WHERE (e.type = 0) ) a  ) b WHERE doctrine_rownum BETWEEN 3 AND 7');
    }
    
    public function testLimitOffsetLimitSubqueriesForOracle()
    {
        $this->dbh = new Doctrine_Adapter_Mock('oracle');
        $conn = $this->manager->openConnection($this->dbh);
        $q = new Doctrine_Query($conn);
        $q->from('User u')->innerJoin('u.Phonenumber p')->limit(5)->offset(2);
        
        $correctSql = "SELECT e.id AS e__id, e.name AS e__name, e.loginname AS e__loginname, "
                            . "e.password AS e__password, e.type AS e__type, e.created AS e__created, "
                            . "e.updated AS e__updated, e.email_id AS e__email_id, p.id AS p__id, "
                            . "p.phonenumber AS p__phonenumber, p.entity_id AS p__entity_id "
                    . "FROM entity e "
                    . "INNER JOIN phonenumber p ON e.id = p.entity_id "
                    . "WHERE e.id IN ("
                        . "SELECT b.id FROM ( "
                            . "SELECT a.*, ROWNUM AS doctrine_rownum "
                            . "FROM ( "
                                . "SELECT DISTINCT e2.id "
                                . "FROM entity e2 "
                                . "INNER JOIN phonenumber p2 ON e2.id = p2.entity_id "
                                . "WHERE (e2.type = 0) "
                            . ") a "
                        . " ) b "
                        . "WHERE doctrine_rownum BETWEEN 3 AND 7"
                    . ") AND (e.type = 0)";
        
        $this->assertEqual($q->getSqlQuery(), $correctSql);
    }
    
 /**
 * Ticket #1038
  */
    public function testLimitOffsetLimitSubqueriesForOracleWithGroupByOrderBy()
    {
        $this->dbh = new Doctrine_Adapter_Mock('oracle');
        $conn = $this->manager->openConnection($this->dbh);
        $q = new Doctrine_Query($conn);
        // The orderBy(p.id) will force p.id to be added to the SELECT part of the 
        // SELECT DISTINCT subquery because that is required by oracle. This, however,
        // can result in duplicated primary keys that would cause incorrect ROWNUM calculations,
        // hence an additional subquery used to filter out the primary keys is added. 
        $q->from('User u')->innerJoin('u.Phonenumber p')
                ->groupBy('u.name') // !
                ->orderBy('p.id') // !!
                ->limit(5)->offset(2);
        $correctSql = "SELECT e.id AS e__id, e.name AS e__name, e.loginname AS e__loginname, "
                    . "e.password AS e__password, e.type AS e__type, e.created AS e__created, "
                    . "e.updated AS e__updated, e.email_id AS e__email_id, p.id AS p__id, "
                    . "p.phonenumber AS p__phonenumber, p.entity_id AS p__entity_id "
                    . "FROM entity e "
                    . "INNER JOIN phonenumber p ON e.id = p.entity_id "
                    . "WHERE e.id IN ("
                        . "SELECT b.id FROM ( "
                            . "SELECT a.*, ROWNUM AS doctrine_rownum "
                                  . "FROM ( "
                                      . "SELECT doctrine_subquery_alias.id FROM ("
                                          . "SELECT e2.id, p2.id "
                                          . "FROM entity e2 "
                                          . "INNER JOIN phonenumber p2 ON e2.id = p2.entity_id "
                                          . "WHERE (e2.type = 0) GROUP BY e2.name ORDER BY p2.id"
                                      . ") doctrine_subquery_alias GROUP BY doctrine_subquery_alias.id ORDER BY MIN(ROWNUM) "
                                  . ") a "
                              . " ) b "
                              . "WHERE doctrine_rownum BETWEEN 3 AND 7"
                          . ") AND (e.type = 0) GROUP BY e.name ORDER BY p.id";
                         
              $this->assertEqual($q->getSqlQuery(), $correctSql);
          }
}

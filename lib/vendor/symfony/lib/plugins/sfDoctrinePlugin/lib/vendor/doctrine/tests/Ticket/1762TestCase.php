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
 * Doctrine_Ticket_1762_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1762_TestCase extends Doctrine_UnitTestCase 
{
    public function testTest()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
        $adapter = new Doctrine_Adapter_Mock('pgsql');
        $conn = Doctrine_Manager::connection($adapter);
        $profiler = new Doctrine_Connection_Profiler();
        $conn->setListener($profiler);

        $query = Doctrine_Query::create()
            ->from('User2 u')
            ->leftJoin('u.Roles')
            ->orderBy('u.id');
        $pager = new Doctrine_Pager($query, 1, 20);
        $records = $pager->execute($conn);

        $queries = $adapter->getAll();
        $this->assertEqual($queries[0], 'SELECT COUNT(*) AS "num_results" FROM (SELECT "u"."id" FROM "user2" "u" LEFT JOIN "user_role2" "u2" ON ("u"."id" = "u2"."user_id") LEFT JOIN "role2" "r" ON "r"."id" = "u2"."role_id" GROUP BY "u"."id") "dctrn_count_query"');
        $this->assertEqual($queries[1], 'SELECT "u"."id" AS "u__id", "u"."username" AS "u__username", "r"."id" AS "r__id", "r"."name" AS "r__name" FROM "user2" "u" LEFT JOIN "user_role2" "u2" ON ("u"."id" = "u2"."user_id") LEFT JOIN "role2" "r" ON "r"."id" = "u2"."role_id" WHERE "u"."id" IN (SELECT "doctrine_subquery_alias"."id" FROM (SELECT DISTINCT "u3"."id" FROM "user2" "u3" LEFT JOIN "user_role2" "u4" ON ("u3"."id" = "u4"."user_id") LEFT JOIN "role2" "r2" ON "r2"."id" = "u4"."role_id" ORDER BY "u3"."id" LIMIT 20) AS "doctrine_subquery_alias") ORDER BY "u"."id"');
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, false);
    }
}

class User2 extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('username', 'string', 255);
    }

    public function setUp()
    {
        $this->hasMany('Role2 as Roles', array('refClass' => 'UserRole2', 
                                                          'local'    => 'user_id',
                                                          'foreign'  => 'role_id'));
    }
}

class Role2 extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
    }

    public function setUp()
    {
        $this->hasMany('User2 as Users', array('refClass' => 'UserRole2', 
                                                          'local'    => 'role_id',
                                                          'foreign'  => 'User_id'));
    }
}

class UserRole2 extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('user_id', 'integer', null, array('primary' => true));
        $this->hasColumn('role_id', 'integer', null, array('primary' => true));
    }
}
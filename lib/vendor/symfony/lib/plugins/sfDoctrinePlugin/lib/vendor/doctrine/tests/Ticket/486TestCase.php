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
 * Doctrine_Ticket_486_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_486_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables() {
        $this->tables = array('Country', 'State', 'Resort');
        parent::prepareTables();
    }


    public function prepareData()
    {
        // Countries
        $c1 = $this->createCountry('Argentina');
        $c2 = $this->createCountry('Brazil');
        $c3 = $this->createCountry('England');
        $c4 = $this->createCountry('Russia');

        // States
        $s1 = $this->createState($c1, 'Buenos Aires');
        $s2 = $this->createState($c1, 'Chaco');
        $s3 = $this->createState($c1, 'Santa Fé');

        $s4 = $this->createState($c2, 'Rio de Janeiro');
        $s5 = $this->createState($c2, 'São Paulo');

        $s6 = $this->createState($c3, 'Hampshire');
        $s7 = $this->createState($c3, 'Yorkshire');

        $s8 = $this->createState($c4, 'Yamalia');

        // Resorts
        $r1 = $this->createResort($s1, 'Punta del Sol');
        $r2 = $this->createResort($s2, 'Los Chacos');
        $r3 = $this->createResort($s2, 'Cuesta del Sol');

        $r4 = $this->createResort($s4, 'Copacabana Palace');
        $r5 = $this->createResort($s5, 'Anacã');

        $r6 = $this->createResort($s7, 'Inn');

        $r7 = $this->createResort($s8, 'Hilton');
    }


    public function testLimitSubqueryQuoteIdentifier()
    {
        // Change the quote identifier
        $curQuoteIdentifier = $this->getConnection()->getAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER);
        $this->getConnection()->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);

        $q = Doctrine_Query::create()
            ->select('c.id')
            ->from('Country c, c.State.Resort r')
            ->where('r.id = 3')
            ->limit(1);

        $this->assertEqual('SELECT "c"."id" AS "c__id" FROM "country" "c" LEFT JOIN "state" "s" ON "c"."id" = "s"."country_id" LEFT JOIN "resort" "r" ON "s"."id" = "r"."state_id" WHERE "c"."id" IN (SELECT DISTINCT "c2"."id" FROM "country" "c2" LEFT JOIN "state" "s2" ON "c2"."id" = "s2"."country_id" LEFT JOIN "resort" "r2" ON "s2"."id" = "r2"."state_id" WHERE "r2"."id" = 3 LIMIT 1) AND ("r"."id" = 3)', $q->getSqlQuery());

        // Restoring quote identifier
        $this->getConnection()->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, $curQuoteIdentifier);
    }


    public function createCountry($name)
    {
        $tmp = new Country();
        $tmp->name = $name;
        $tmp->save();

        return $tmp;
    }


    public function createState($country, $name)
    {
        $tmp = new State();
        $tmp->name = $name;
        $tmp->Country = $country;
        $tmp->save();

        return $tmp;
    }


    public function createResort($state, $name)
    {
        $tmp = new Resort();
        $tmp->name = $name;
        $tmp->State = $state;
        $tmp->save();

        return $tmp;
    }
}


class Country extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
    }


    public function setUp()
    {
        $this->hasMany('State', array('local' => 'id', 'foreign' => 'country_id'));
    }
}


class State extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('country_id', 'integer', 4);
        $this->hasColumn('name', 'string', 255);
    }


    public function setUp()
    {
        $this->hasOne('Country', array('local' => 'country_id', 'foreign' => 'id'));
        $this->hasMany('Resort', array('local' => 'id', 'foreign' => 'state_id'));
    }
}


class Resort extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('state_id', 'integer', 4);
        $this->hasColumn('name', 'string', 255);
    }


    public function setUp()
    {
        $this->hasOne('State', array('local' => 'state_id', 'foreign' => 'id'));
    }
}
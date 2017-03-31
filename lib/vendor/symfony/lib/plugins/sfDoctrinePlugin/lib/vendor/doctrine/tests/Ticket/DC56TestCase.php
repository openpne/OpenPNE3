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
 * Doctrine_Ticket_DC56_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC56_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC56_Location';
        parent::prepareTables();
    }

    public function testTest()
    {
        $test = new Ticket_DC56_Location();
        $test->lat = 50;
        $test->long = 50;
        $q = $test->getDistanceQuery();
        $this->assertEqual($q->getSqlQuery(), 'SELECT t.id AS t__id, t.title AS t__title, t.lat AS t__lat, t.long AS t__long, ((ACOS(SIN(50 * PI() / 180) * SIN(t.lat * PI() / 180) + COS(50 * PI() / 180) * COS(t.lat * PI() / 180) * COS((50 - t.long) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS t__0, ((ACOS(SIN(50 * PI() / 180) * SIN(t.lat * PI() / 180) + COS(50 * PI() / 180) * COS(t.lat * PI() / 180) * COS((50 - t.long) * PI() / 180)) * 180 / PI()) * 60 * 1.1515 * 1.609344) AS t__1 FROM ticket__d_c56__location t');
    }
}

class Ticket_DC56_Location extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('title', 'string', 255);
    }

    public function setUp()
    {
        $this->actAs('Geographical', array(
            'latitude' => array(
                'name' => 'lat'
            ),
            'longitude' => array(
                'name' => 'long'
            )
        ));
    }
}
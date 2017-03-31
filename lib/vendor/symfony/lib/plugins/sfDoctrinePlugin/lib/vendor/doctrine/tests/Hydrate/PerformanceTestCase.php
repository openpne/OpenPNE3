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
 * Doctrine_Hydrate_Scalar_TestCase
 *
 * @package     Doctrine
 * @author      Roman Borschel <roman@code-factory.org>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.1
 * @version     $Revision$
 */
class Doctrine_Hydrate_Performance_TestCase extends Doctrine_UnitTestCase
{
    public function prepareData()
    {
        for ($i = 0; $i < 10000; $i++)
        {
            $test = new HydratePerformance();
            for ($j = 1; $j <= 6; $j++)
            {
                $test->set('column'.$j, 'Test value');
            }
            $test->save();
        }
    }

    public function prepareTables()
    {
        $this->tables[] = 'HydratePerformance';
        parent::prepareTables();
    }

    public function testPerformance()
    {
        $s = microtime(true);

        $q = Doctrine_Core::getTable('HydratePerformance')
            ->createQuery('u');

        $records = $q->execute();

        $e = microtime(true);
        $time = $e - $s;

        echo PHP_EOL.'Hydration for ' . count($records) . ' records took ' . $time . PHP_EOL;
    }
}

class HydratePerformance extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('column1', 'string', 255);
        $this->hasColumn('column2', 'string', 255);
        $this->hasColumn('column3', 'string', 255);
        $this->hasColumn('column4', 'string', 255);
        $this->hasColumn('column5', 'string', 255);
        $this->hasColumn('column6 ', 'string', 255);
    }
}
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
 * Doctrine_I18nRelation_TestCase
 *
 * @package     Doctrine
 * @author      Brice Figureau <brice+doctrine@daysofwonder.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1028_TestCase extends Doctrine_UnitTestCase
{

    public function prepareData()
    { }

    public function prepareTables()
    {
        $this->tables = array('I18nRelationTest','I18nAuthorTest');

        parent::prepareTables();
    }

    public function testRelationIsNotInOriginalTableAnymore()
    {
        $i18n = Doctrine_Core::getTable('I18nRelationTest');
        $relation = NULL;
        try {
            $relation = $i18n->getRelation('I18nAuthorTest');
            $this->fail();
        } catch(Doctrine_Exception $e) {
            $this->pass();
        }
    }


    public function testRelationsAreMovedToTranslationTable()
    {
        $translation = Doctrine_Core::getTable('I18nRelationTestTranslation');
        $relation = NULL;
        try {
            $relation = $translation->getRelation('I18nAuthorTest');
            $this->pass();
        } catch(Doctrine_Exception $e) {
            $this->fail();
        }
        $this->assertTrue($relation instanceof Doctrine_Relation_LocalKey);
    }

   
}
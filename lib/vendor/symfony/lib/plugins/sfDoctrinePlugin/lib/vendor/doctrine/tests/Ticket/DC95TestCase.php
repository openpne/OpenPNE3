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
 * Doctrine_Ticket_DC95_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC95_TestCase extends Doctrine_UnitTestCase 
{
    public function testClassDoesNotExistBeforeImport()
    {
        $this->assertFalse(class_exists('Base_DC95_Article'));
        $this->assertFalse(class_exists('Base_DC95_Article_Category'));
        $this->assertFalse(class_exists('DC95_Article'));
        $this->assertFalse(class_exists('DC95_Article_Category'));
    }

    public function testClassExistsAfterImport()
    {
        Doctrine_Core::setModelsDirectory(dirname(__FILE__) . '/DC95/models');

        $import = new Doctrine_Import_Schema();
        $import->setOptions(array(
            'pearStyle' => true,
            'baseClassesDirectory' => null,
            'baseClassPrefix' => 'Base_',
            'classPrefix' => 'DC95_',
            'classPrefixFiles' => true
        ));
        $modelsPath = dirname(__FILE__) . '/DC95/models';
        $import->importSchema(dirname(__FILE__) . '/DC95/schema.yml', 'yml', $modelsPath);

        /*
        $this->assertTrue(file_exists($modelsPath . '/DC95/Base/Article.php'));
        $this->assertTrue(file_exists($modelsPath . '/DC95/Base/Article/Category.php'));
        $this->assertTrue(file_exists($modelsPath . '/DC95/Article.php'));
        $this->assertTrue(file_exists($modelsPath . '/DC95/Article/Category.php'));
        */

        Doctrine_Core::setModelsDirectory(null);
        Doctrine_Lib::removeDirectories(dirname(__FILE__) . '/DC95/models');
    }
}
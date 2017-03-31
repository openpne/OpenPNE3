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
 * Doctrine_Ticket_384_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_384_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareData()
    {
	    $oResume = new ticket384_Resume;
	    $oResume->title = 'titre';
	    $oResume->KnownLanguages[0]->comments = 'foo';
	    $oResume->KnownLanguages[0]->Language->label = "Enlish";
	    $oResume->KnownLanguages[0]->Level->label = "Fluent";
	    $oResume->save();
    }

    public function prepareTables()
    {
    	$this->tables = array();
    	$this->tables[] = 'ticket384_Resume';
    	$this->tables[] = 'ticket384_ResumeHasLanguage';
    	$this->tables[] = 'ticket384_LanguageLevel';
    	$this->tables[] = 'ticket384_Language';
    	
    	parent :: prepareTables();
    }

    public function testTicket()
    {
        $q = new Doctrine_Query();

        // simple query with deep relations
        $q->addSelect('Resume.id, Level.id, Level.label')
          ->from('ticket384_Resume Resume')
          ->leftJoin('Resume.KnownLanguages KnownLanguages')
          ->leftJoin('KnownLanguages.Level Level')
          ->leftJoin('KnownLanguages.Language Language');
        
        try {
            // get the wrong resultset
            $aResult = $q->fetchArray();
            $this->fail();
        } catch (Doctrine_Query_Exception $e) {
            $this->pass();
        } 
        $q->free();
        
        // now correct
        // we have to select at least KnownLanguages.id in order to get the Levels,
        // which are only reachable through the KnownLanguages, hydrated properly.
        $q = new Doctrine_Query();
        $q->addSelect('Resume.id, Level.id, Level.label, KnownLanguages.id')
          ->from('ticket384_Resume Resume')
          ->leftJoin('Resume.KnownLanguages KnownLanguages')
          ->leftJoin('KnownLanguages.Level Level')
          ->leftJoin('KnownLanguages.Language Language');
        
        $aResult = $q->fetchArray();
        // should be setted
        $bSuccess  = isset($aResult[0]['KnownLanguages'][0]['Level']);
        $this->assertTrue($bSuccess);
    	  
    	  if ( ! $bSuccess)
    	  {
    	     $this->fail('fetchArray doesnt hydrate nested child relations, if parent doesnt have a column selected');
    	  }
    }
}

class ticket384_Resume extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('resume');
    $this->hasColumn('id', 'integer', 8, array (
		  'primary' => true,
		  'autoincrement' => true,
		  'unsigned' => true,
		  ));
		  
    $this->hasColumn('title', 'string', 255);
  }

  public function setUp()
  {
    $this->hasMany('ticket384_ResumeHasLanguage as KnownLanguages', array('local' => 'id', 'foreign' => 'resume_id'));
  } 
}

class ticket384_ResumeHasLanguage extends Doctrine_Record
{	
  public function setTableDefinition()
  {
    $this->setTableName('resume_has_language');
    $this->hasColumn('id', 'integer', 8, array (
		  'primary' => true,
		  'autoincrement' => true,
		  'unsigned' => true,
		  ));

    $this->hasColumn('resume_id', 'integer', 8, array (
		  'notnull' => true,
		  'unsigned' => true,
		  ));

    $this->hasColumn('language_id', 'integer', 2, array (
      'unsigned' => true,
      ));

    $this->hasColumn('language_level_id', 'integer', 2, array (
      'unsigned' => true,
      ));
    
    $this->hasColumn('comments', 'string', 4000, array ());

  }

  public function setUp()
  {
    $this->hasOne('ticket384_Resume as Resume', array('local' => 'resume_id',
                                  'foreign' => 'id',
                                  'onDelete' => 'CASCADE',
                                  'onUpdate' => 'CASCADE'));

    $this->hasOne('ticket384_Language as Language', array('local' => 'language_id',
                                    'foreign' => 'id',
                                    'onDelete' => 'CASCADE',
                                    'onUpdate' => 'CASCADE'));

    $this->hasOne('ticket384_LanguageLevel as Level', array('local' => 'language_level_id',
                                                  'foreign' => 'id',
                                                  'onDelete' => 'SET NULL',
                                                  'onUpdate' => 'CASCADE'));
  } 
}

class ticket384_Language extends Doctrine_Record
{	
  public function setTableDefinition()
  {
  	$this->setTableName('language');
    $this->hasColumn('id', 'integer', 2, array(
      'primary' => true,
      'autoincrement' => true,
      'unsigned' => true,
      ));

    $this->hasColumn('label', 'string', 100, array ('notnull' => true));
  }
  
  public function setUp()
  {
    $this->hasMany('ticket384_Resume as Resumes', array('local' => 'id', 'foreign' => 'language_id'));
    $this->hasMany('ticket384_ResumeHasLanguage as ResumeKnownLanguages', array('local' => 'id', 'foreign' => 'language_id'));
  } 
}

class ticket384_LanguageLevel extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('language_level');
    $this->hasColumn('id', 'integer', 2, array (
		  'primary' => true,
		  'autoincrement' => true,
		  'unsigned' => true,
		  ));

    $this->hasColumn('label', 'string', 100, array ('notnull' => true));
  }

  public function setUp()
  {
    $this->hasMany('ticket384_ResumeHasLanguage as ResumeKnownLanguages', array(
      'local'   => 'id',
      'foreign' => 'language_level_id'));
  }
}
<?php

include_once dirname(__FILE__).'/../../bootstrap/unit.php';

$_app = 'pc_frontend';
$_env = 'test';

$configuration = ProjectConfiguration::getApplicationConfiguration($_app, $_env, true);
new sfDatabaseManager($configuration);

$t = new lime_test(null, new lime_output_color());

// --

$zlibEnabled = extension_loaded('zlib');

class MyRecord extends opDoctrineRecord
{
  public function __construct()
  {
    parent::__construct();

    $types = array(
      'string', 'array', 'object', 'blob', 'clob', 'char', 'varchar',
    );

    global $zlibEnabled;
    if ($zlibEnabled)
    {
      $types[] = 'gzip';
    }

    foreach ($types as $type)
    {
      $this->hasColumn('column_'.$type, $type);
    }
  }
}

$strRaw = 'testⓉⒺⓈⓉテスト🅃🄴🅂🅃てすと'; // 1 byte characters + 2 bytes characters + 3 bytes characters + 4 bytes characters + 3 bytes characters
$strConverted = 'testⓉⒺⓈⓉテスト����てすと';

$r = new MyRecord();
$r->setColumnString($strRaw);
$r->setColumnArray(array($strRaw => $strRaw, $strRaw => array($strRaw)));
$r->setColumnBlob($strRaw);
$r->setColumnClob($strRaw);
$r->setColumnChar($strRaw);
$r->setColumnVarchar($strRaw);
if ($zlibEnabled)
{
  $r->setColumnGzip($strRaw);
}

$result = $r->getPrepared();

// --

$t->is($result['column_string'], $strConverted, '4 bytes UTF-8 characters in a string are replaced');
$t->is($result['column_array'], serialize(array($strConverted => $strConverted, $strConverted => array($strConverted))), '4 bytes UTF-8 characters in an array are replaced');
$t->is($result['column_blob'], $strRaw, '4 bytes UTF-8 characters in a blob are NOT replaced');
$t->is($result['column_clob'], $strConverted, '4 bytes UTF-8 characters in a clob are replaced');
$t->is($result['column_char'], $strConverted, '4 bytes UTF-8 characters in a char are replaced');
$t->is($result['column_varchar'], $strConverted, '4 bytes UTF-8 characters in a varchar are replaced');
if ($zlibEnabled)
{
  $t->is($result['column_gzip'], gzcompress($strRaw, 5), '4 bytes UTF-8 characters in a gzip are NOT replaced');
}
else
{
  $t->skip('the test for gzip type column (missing zlib extention)');
}

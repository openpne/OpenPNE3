<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(11, new lime_output_color());
$file1 = Doctrine::getTable('File')->find(1);
$file2 = Doctrine::getTable('File')->find(2);
$file3 = Doctrine::getTable('File')->find(3);

$tmpDir = sys_get_temp_dir();
$content = b'This is an ASCII file.';
file_put_contents($tmpDir.'/test.txt', $content);

//------------------------------------------------------------
$t->diag('File');
$t->diag('File::__toString()');
$t->is((string)$file1, 'dummy_file');

//------------------------------------------------------------
$t->diag('File::getImageFormat()');
$t->is($file1->getImageFormat(), 'png');
$t->is($file2->getImageFormat(), 'jpg');
$t->is($file3->getImageFormat(), false);

//------------------------------------------------------------
$t->diag('File::isImage()');
$t->is($file1->isImage(), true);
$t->is($file2->isImage(), true);
$t->is($file3->isImage(), false);

//------------------------------------------------------------
$t->diag('File::setFromValidatedFile()');
// require
new sfValidatorFile();
$validated = new sfValidatedFile('test.txt', 'text/plain', $tmpDir.'/test.txt', strlen($content));
$newFile = new file();
$newFile->setFromValidatedFile($validated);
$t->is((string)$newFile->getOriginalFilename(), 'test.txt');

//------------------------------------------------------------
$t->diag('File::getFilesize()');
$newFile->save();
$t->is($newFile->getFilesize(), 22, 'The "file.file_size" is stored.');

//-----------------------------------------------------------
$t->diag('->delete()');
$file3->delete();
$t->ok(!Doctrine::getTable('File')->find(3), 'The parent "File" record is removed.');
$t->todo('The related "FileBin" record is removed.');


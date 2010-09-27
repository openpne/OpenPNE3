<?php

include_once dirname(__FILE__).'/../../bootstrap/unit.php';
sfContext::createInstance(ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true));

include_once sfConfig::get('sf_lib_dir').'/vendor/symfony/lib/helper/HelperHelper.php';
use_helper('Tag');

$t = new lime_test(27, new lime_output_color());

//------------------------------------------------------------
$t->diag('opWidgetFormRichTextareaOpenPNE::toHtml() non strip');

$patterns = array(
  array(
    '&lt;op:s&gt;どーん&lt;/op:s&gt;',
    '<span class="op_s">どーん</span>',
  ),
  array(
    '&lt;op:s&gt;どどー&lt;op&gt;ん&lt;/op:s&gt;',
    '<span class="op_s">どどー&lt;op&gt;ん</span>',
  ),
  array(
    '&lt;op:s&gt;どどー',
    '<span class="op_s">どどー</span>',
  ),
  array(
    '&lt;op:a&lt;op:i&gt;',
    '&lt;op:a<span class="op_i"></span>',
  ),
  array(
    '&lt;op:i color="#333&lt;op:i&gt;"&gt;#333&lt;/op:i&gt;',
    '&lt;op:i color="#333<span class="op_i">"&gt;#333</span>',
  ),
  array(
    '&lt;op:font color="#333333"&gt;#333&lt;/op:font&gt;',
    '<span class="op_font" style="color:#333333;">#333</span>',
  ),
  array(
    '&lt;op:tetetetetete0111111&gt;',
    '<span class="op_tetetetetete0111111"></span>',
  ),
  array(
    '&lt;op:i<br />&gt;&lt;op:',
    '&lt;op:i<br />&gt;&lt;op:',
  ),
  array(
    '&lt;op:font color="expression(alert(0))"&gt;Attack!&lt;/op:font&gt;',
    '<span class="op_font" style="">Attack!</span>',
  ),
);
foreach ($patterns as $pattern)
{
  $t->is(opWidgetFormRichTextareaOpenPNE::toHtml($pattern[0], false, true, true), $pattern[1]);
}
//------------------------------------------------------------
$t->diag('opWidgetFormRichTextareaOpenPNE::toHtml() strip');

$patterns2 = array(
  array(
    '&lt;op:s&gt;どーん&lt;/op:s&gt;',
    'どーん',
  ),
  array(
    '&lt;op:s&gt;どどー&lt;op&gt;ん&lt;/op:s&gt;',
    'どどー&lt;op&gt;ん',
  ),
  array(
    '&lt;op:s&gt;どどー',
    'どどー',
  ),
  array(
    '&lt;op:a&lt;op:i&gt;',
    '&lt;op:a',
  ),
  array(
    '&lt;op:i color="#333&lt;op:i&gt;"&gt;#333&lt;/op:i&gt;',
    '&lt;op:i color="#333"&gt;#333',
  ),
  array(
    '&lt;op:font color="#333"&gt;#333&lt;/op:font&gt;',
    '#333',
  ),
  array(
    '&lt;op:tetetetetete0111111&gt;',
    '',
  ),
  array(
    '&lt;op:i<br />&gt;&lt;op:',
    '&lt;op:i<br />&gt;&lt;op:',
  ),
  array(
    '&lt;op:font color="expression(alert(0))"&gt;Attack!&lt;/op:font&gt;',
    'Attack!',
  ),
);
foreach ($patterns2 as $pattern2)
{
  $t->is(opWidgetFormRichTextareaOpenPNE::toHtml($pattern2[0], true, true, true), $pattern2[1]);
}
//------------------------------------------------------------
$t->diag('opWidgetFormRichTextareaOpenPNE::toHtml() followup');

$patterns3 = array(
  array(
    '&lt;op:s&gt;どーん',
    '<span class="op_s">どーん</span>',
  ),
  array(
    '&lt;op:s&gt;どどー&lt;op&gt;ん',
    '<span class="op_s">どどー&lt;op&gt;ん</span>',
  ),
  array(
    '&lt;op:s&gt;どどー',
    '<span class="op_s">どどー</span>',
  ),
  array(
    '&lt;op:a&lt;op:i&gt;',
    '&lt;op:a<span class="op_i"></span>',
  ),
  array(
    '&lt;op:i color="#333&lt;op:i&gt;"&gt;#333',
    '&lt;op:i color="#333<span class="op_i">"&gt;#333</span>',
  ),
  array(
    '&lt;op:font color="#333333"&gt;#333',
    '<span class="op_font" style="color:#333333;">#333</span>',
  ),
  array(
    '&lt;op:tetetetetete0111111&gt;',
    '<span class="op_tetetetetete0111111"></span>',
  ),
  array(
    '&lt;op:i&gt;&lt;op:&lt;op:i&gt;&lt;op:i&gt;&lt;op:i&gt;&lt;op:333333&gt;',
    '<span class="op_i">&lt;op:<span class="op_i"><span class="op_i"><span class="op_i"><span class="op_333333"></span></span></span></span></span>',
  ),
  array(
    '&lt;op:font color="expression(alert(0))"&gt;Attack!',
    '<span class="op_font" style="">Attack!</span>',
  ),
);
foreach ($patterns3 as $pattern3)
{
  $t->is(opWidgetFormRichTextareaOpenPNE::toHtml($pattern3[0], false, true, true), $pattern3[1]);
}

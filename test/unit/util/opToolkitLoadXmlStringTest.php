<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new lime_test(null, new lime_output_color());

$t->diag('opToolkit::loadXmlString()');

$path_to_feed = realpath(dirname(__FILE__).'/../../fixtures/feeds/www.xss.feed.rss');
$xml = '<a id="root">ok</a>';
$xml_with_xxe = '<!DOCTYPE a [<!ENTITY xxe SYSTEM "file://'.$path_to_feed.'">]><a id="root">ok&xxe;</a>';

$t->comment('with no external entities');

$t->isa_ok(opToolkit::loadXmlString($xml), 'DOMDocument', 'returns an instance of "DOMDocument"');
$t->isa_ok(opToolkit::loadXmlString($xml, array('return' => 'SimpleXMLElement')), 'SimpleXMLElement', 'returns an instanceof "SimpleXMLElement"');

$t->comment('with external entities');

$t->isa_ok(opToolkit::loadXmlString($xml_with_xxe), 'DOMDocument', 'returns an instance of "DOMDocument"');
$t->isa_ok(opToolkit::loadXmlString($xml_with_xxe, array('return' => 'SimpleXMLElement')), 'SimpleXMLElement', 'returns an instanceof "SimpleXMLElement"');
$t->is(opToolkit::loadXmlString($xml_with_xxe)->textContent, 'ok', 'generated XML string by "DOMDocument" does not have entitied value');
$t->is((string)opToolkit::loadXmlString($xml_with_xxe, array('return' => 'SimpleXMLElement')), 'ok', 'generated XML string by "SimpleXMLElement" does not have entitied value');
$t->todo('generated XML string by "DOMDocument" has entitied value if "loadEntities" option is specified');
$t->todo('generated XML string by "SimpleXMLElement" has entitied value if "loadEntities" option is specified');

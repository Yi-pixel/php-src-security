--TEST--
GHSA-3qrf-m4j2-pcrr (libxml global state entity loader bypass)
--SKIPIF--
<?php
if (!extension_loaded('libxml')) die('skip libxml extension not available');
if (!extension_loaded('xmlreader')) die('skip xmlreader extension not available');
if (!extension_loaded('zend-test')) die('skip zend-test extension not available');
if (!function_exists('zend_test_override_libxml_global_state')) die('skip not for Windows');
?>
--FILE--
<?php

$xml = "<?xml version='1.0'?><!DOCTYPE root [<!ENTITY % bork SYSTEM \"php://nope\"> %bork;]><nothing/>";

libxml_use_internal_errors(true);
zend_test_override_libxml_global_state();

echo "--- String test ---\n";
$reader = @XMLReader::xml($xml);
$reader->read();
echo "--- File test ---\n";
file_put_contents("libxml_global_state_entity_loader_bypass.tmp", $xml);
$reader = @XMLReader::open("libxml_global_state_entity_loader_bypass.tmp");
$reader->read();

echo "Done\n";

?>
--CLEAN--
<?php
@unlink("libxml_global_state_entity_loader_bypass.tmp");
?>
--EXPECT--
--- String test ---
--- File test ---
Done

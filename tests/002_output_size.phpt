--TEST--
blake3('Hello world', 20) returns expected shortened hash
--SKIPIF--
<?php if (!extension_loaded('blake3')) die('skip blake3 extension required'); ?>
--FILE--
<?php
var_dump(blake3('Hello world', 20));
?>
--EXPECT--
string(40) "e7e6fb7d2869d109b62cdb1227208d4016cdaa0a"

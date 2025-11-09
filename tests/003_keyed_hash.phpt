--TEST--
blake3('Hello world', 32, key) returns expected hash
--SKIPIF--
<?php if (!extension_loaded('blake3')) die('skip blake3 extension required'); ?>
--FILE--
<?php
var_dump(blake3('Hello world', 32, 'cae8954e7b3415ea18303db548e15207'));
?>
--EXPECT--
string(64) "75672fafd13480d2325914f0665795eceecad4e668d9ea2a87c40e71232a7d3a"

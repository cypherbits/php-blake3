--TEST--
Extended output length for 64-byte request
--SKIPIF--
<?php if (!extension_loaded('blake3')) die('skip blake3 extension required'); ?>
--FILE--
<?php
$hex = blake3('abc', 64);
var_dump(strlen($hex));
?>
--EXPECT--
int(128)

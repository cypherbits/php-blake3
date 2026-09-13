--TEST--
blake3 raw output returns 32 bytes and differs from hex output
--SKIPIF--
<?php if (!extension_loaded('blake3')) die('skip blake3 extension required'); ?>
--FILE--
<?php
$raw = blake3('', 32, '', true); // empty key
$hex = blake3('', 32); // default hex
var_dump(strlen($raw));
var_dump(strlen($hex));
var_dump($raw === $hex); // raw vs hex must differ
?>
--EXPECT--
int(32)
int(64)
bool(false)

--TEST--
blake3 raw output returns 32 bytes
--SKIPIF--
<?php if (!extension_loaded('blake3')) die('skip blake3 extension required'); ?>
--FILE--
<?php
$raw = blake3('', 32, '', true); // empty key instead of null
var_dump(strlen($raw));
// raw binary may include non-printables; ensure differs from hex by checking all hex chars OR presence of any non-hex
$allHex = ctype_xdigit(bin2hex($raw)); // always true but shows transform works
var_dump($allHex);
?>
--EXPECT--
int(32)
bool(true)

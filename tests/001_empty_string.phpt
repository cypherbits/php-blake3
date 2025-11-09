--TEST--
blake3('') returns expected hex (32 bytes)
--SKIPIF--
<?php if (!extension_loaded('blake3')) die('skip blake3 extension required'); ?>
--FILE--
<?php
var_dump(blake3(''));
?>
--EXPECT--
string(64) "af1349b9f5f9a1a6a0404dea36dcc9499bcb25c9adc112b7cc9a93cae41f3262"


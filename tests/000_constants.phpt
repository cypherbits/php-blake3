--TEST--
BLAKE3_OUT_LEN constant is defined and equals 32
--SKIPIF--
<?php if (!extension_loaded('blake3')) die('skip blake3 extension required'); ?>
--FILE--
<?php
var_dump(defined('BLAKE3_OUT_LEN'));
var_dump(BLAKE3_OUT_LEN);
?>
--EXPECT--
bool(true)
int(32)


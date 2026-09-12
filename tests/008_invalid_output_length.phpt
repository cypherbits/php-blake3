--TEST--
blake3 with output length 0 triggers fatal error
--SKIPIF--
<?php
if (getenv('CUSTOM_PHPT_RUNNER')) die('skip custom runner does not capture fatal');
if (!extension_loaded('blake3')) die('skip blake3 extension required');
?>
--FILE--
<?php
blake3('abc', 0);
echo "unreached\n";
?>
--EXPECTF--
Fatal error: BLAKE3 output length cannot be zero in %s on line %d

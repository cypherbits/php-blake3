--TEST--
blake3 with invalid key length triggers fatal error
--SKIPIF--
<?php
if (getenv('CUSTOM_PHPT_RUNNER')) die('skip custom runner does not capture fatal');
if (!extension_loaded('blake3')) die('skip blake3 extension required');
?>
--FILE--
<?php
blake3('abc', 32, 'shortkey');
echo "unreached\n";
?>
--EXPECTF--
Fatal error: BLAKE3 key length MUST be 32 bytes in %s on line %d

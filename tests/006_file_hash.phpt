--TEST--
blake3_file matches blake3() on file contents
--SKIPIF--
<?php if (!extension_loaded('blake3')) die('skip blake3 extension required'); ?>
--FILE--
<?php
$fn = __DIR__ . '/tmp.txt';
file_put_contents($fn, 'Hello world');
$hFile = blake3_file($fn);
$hMem  = blake3('Hello world');
var_dump($hFile === $hMem);
unlink($fn);
?>
--EXPECT--
bool(true)

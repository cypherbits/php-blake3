PHP BLAKE3 Extension
============================

BLAKE3 is an improved and faster version of BLAKE2.

This extension uses the official BLAKE3 C implementation, thus is single-threaded, but still faster than SHA256 or SHA512 on my benchmark on latest PHP 7.4.

Installation
------------
Clone the repository and compile it:
```sh
$ git clone https://github.com/cypherbits/php-blake3.git
$ cd php-blake3
$ phpize
$ ./configure --enable-blake3
$ make && sudo make install
```

Enable the extension by adding the following line to your php.ini file:

```sh
extension=blake3.so
```

You may need to restart your web server to load the extension.


Usage
----

**Global constants:**

`BLAKE3_OUT_LEN: 32`

**Functions:**

```php
string blake3 ( string $str [, int $outputSize = 64, string $key, bool $rawOutput = false ] )
```

* $str: The string to hash
* $outputSize: The length of the output hash (can be between 1 and 64)
* $key: Turns the output into a keyed hash using the specified key. It MUST be of 32 bytes long.
* $rawOutput: If set to true, then the hash is returned in raw binary format

* Return value: A hex string containing the BLAKE3 hash of the input string. Default output size: 32 bytes.

```php
string blake3_file ( string $filename [, bool $rawOutput = false ] )
```

* $filename: The filename of the file to hash
* $rawOutput: If set to true, then the hash is returned in raw binary format

* Return value: A hex string containing the BLAKE3 hash of the input file

```php
string b3sum ( string $filename [, bool $rawOutput = false ] )
```

is an alias to `blake3_file`


Examples
--------
```php
echo blake3('');
```

af1349b9f5f9a1a6a0404dea36dcc9499bcb25c9adc112b7cc9a93cae41f3262

```php
echo blake3('Hello world', 20);
```

e7e6fb7d2869d109b62cdb1227208d4016cdaa0a

```php
echo blake3('Hello world', 32, 'cae8954e7b3415ea18303db548e15207');
```

75672fafd13480d2325914f0665795eceecad4e668d9ea2a87c40e71232a7d3a


```php
echo b3sum('tests/sample.txt');
```

Outputs : 2c39776c5899720c2a9867d319c9991e197b57c0eb9d7c62f85375794c58b10f

Benchmarks PHP 7.4
--------
```php
<?php

$start = microtime(true);

for ($i=0; $i<1000; $i++){
    $str = md5("hello");
}

$end = microtime(true);

printf("Function call %s took %.5f seconds\n", "md5", $end - $start);

$start = microtime(true);

for ($i=0; $i<1000; $i++){
    $str = sha1("hello");
}

$end = microtime(true);

printf("Function call %s took %.5f seconds\n", "sha1", $end - $start);

$start = microtime(true);

for ($i=0; $i<1000; $i++){
    $str = hash("sha256", "hello");
}

$end = microtime(true);

printf("Function call %s took %.5f seconds\n", "sha256", $end - $start);


$start = microtime(true);

for ($i=0; $i<1000; $i++){
    $str = hash("sha512", "hello");
}

$end = microtime(true);

printf("Function call %s took %.5f seconds\n", "sha512", $end - $start);


$start = microtime(true);

for ($i=0; $i<1000; $i++){
    $str = blake3("hello");
}

$end = microtime(true);

printf("Function call %s took %.5f seconds\n", "blake3", $end - $start);
```

**Results**

As fast as SHA1 but safer.

```php
Function call md5 took 0.00022 seconds
Function call sha1 took 0.00030 seconds
Function call sha256 took 0.00070 seconds
Function call sha512 took 0.00093 seconds
Function call blake3 took 0.00030 seconds
```

More Info
---------
https://github.com/BLAKE3-team/BLAKE3
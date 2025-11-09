PHP BLAKE3 Extension
============================

BLAKE3 is an improved and faster version of BLAKE2.

This extension uses the official BLAKE3 C implementation, thus is single-threaded, but still faster than SHA256 or SHA512 on my benchmark on PHP 7.4.

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
string blake3 ( string $str [, int $outputSize = 32, string $key, bool $rawOutput = false ] )
```

* $str: The string to hash
* $outputSize: The length of the output hash in bytes (must be >= 1). BLAKE3 supports extendable output so larger values are allowed; the default is 32 bytes.
* $key: Turns the output into a keyed hash using the specified key. It MUST be of 32 bytes long.
* $rawOutput: If set to true, then the hash is returned in raw binary format

* Return value: A hex string containing the BLAKE3 hash of the input string. Default output size: 32 bytes.

```php
string blake3_file ( string $filename [, bool $rawOutput = false ] )
```

* $filename: The filename of the file to hash
* $rawOutput: If set to true, then the hash is returned in raw binary format
* Return value: A hex string containing the BLAKE3 hash of the input file

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

Benchmarks
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

**Results PHP 7.4**

As fast as SHA1 but safer.

```php
Function call md5 took 0.00022 seconds
Function call sha1 took 0.00030 seconds
Function call sha256 took 0.00070 seconds
Function call sha512 took 0.00093 seconds
Function call blake3 took 0.00030 seconds
```

**Results PHP 8.0**

As fast as SHA1 but safer.

```php
Function call md5 took 0.00018 seconds
Function call sha1 took 0.00028 seconds
Function call sha256 took 0.00059 seconds
Function call sha512 took 0.00076 seconds
Function call blake3 took 0.00028 seconds
```

**Results PHP 8.0 for a random 4MB file (loop of 100 iterations)**

On file hashing Blake3 wins even over SHA1!

```php
Function call sha512 took 1.69619 seconds
Function call sha256 took 2.51510 seconds
Function call blake3 took 0.60553 seconds
Function call sha1 took 1.03434 seconds
```

More Info
---------
https://github.com/BLAKE3-team/BLAKE3

Donate
------
https://ko-fi.com/cypherbits

Monero address:
`4BCveGZaPM7FejGkhFyHgtjVXZw52RrYxKs7znZdmnWLfB3xDKAW6SkYZPpNhqBvJA8crE8Tug8y7hx8U9KAmq83PwLtVLe`
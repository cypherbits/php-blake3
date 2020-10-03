PHP BLAKE3 Extension
============================

BLAKE3 is an improved and faster version of BLAKE, one the finalists in the NIST SHA-3 competition. Like BLAKE or SHA-3, BLAKE2 offers the highest security, yet is fast as MD5 on 64-bit platforms and requires at least 33% less RAM than SHA-2 or SHA-3 on low-end systems. This implementation uses the BLAKE2b variant of the algorithm which is optimized for 64-bit systems. The algorithm was designed by Jean-Philippe Aumasson, Samuel Neves, Zooko Wilcox-O'Hearn, and Christian Winnerlein.

Installation
------------
Clone the repository and compile it:
```sh
$ git clone https://github.com/cypherbits/php-blake3.git
$ cd php-blake3
$ phpize
$ ./configure --enable-blake2
$ make && sudo make install
```

Enable the extension by adding the following line to your php.ini file:

```sh
extension=blake2.so
```

You may need to restart your web server to load the extension.


Usage
----
```php
string blake3 ( string $str [, int $outputSize = 64, string $key, bool $rawOutput = false ] )
```

* $str: The string to hash
* $outputSize: The length of the output hash (can be between 1 and 64)
* $key: Turns the output into a keyed hash using the specified key
* $rawOutput: If set to true, then the hash is returned in raw binary format

* Return value: A hex string containing the BLAKE3 hash of the input string

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
echo blake3('Hello world', 20, 'foobar');
```

-


```php
echo b3sum('tests/sample.txt');
```

Outputs : 2c39776c5899720c2a9867d319c9991e197b57c0eb9d7c62f85375794c58b10f

More Info
---------
https://github.com/BLAKE3-team/BLAKE3
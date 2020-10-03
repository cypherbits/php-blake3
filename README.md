PHP BLAKE3 Extension
============================

BLAKE3 is an improved and faster version of BLAKE, one the finalists in the NIST SHA-3 competition. Like BLAKE or SHA-3, BLAKE2 offers the highest security, yet is fast as MD5 on 64-bit platforms and requires at least 33% less RAM than SHA-2 or SHA-3 on low-end systems. This implementation uses the BLAKE2b variant of the algorithm which is optimized for 64-bit systems. The algorithm was designed by Jean-Philippe Aumasson, Samuel Neves, Zooko Wilcox-O'Hearn, and Christian Winnerlein.

Installation
------------
Clone the repository and compile it:
```sh
$ git clone git@github.com:strawbrary/php-blake2.git
$ cd php-blake2
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
string blake2 ( string $str [, int $outputSize = 64, string $key, bool $rawOutput = false ] )
```

* $str: The string to hash
* $outputSize: The length of the output hash (can be between 1 and 64)
* $key: Turns the output into a keyed hash using the specified key
* $rawOutput: If set to true, then the hash is returned in raw binary format

* Return value: A hex string containing the BLAKE2 hash of the input string

```php
string blake2b ( string $str [, int $outputSize = 64, string $key, bool $rawOutput = false ] )
```

is an alias to `blake2`


```php
string blake2s ( string $str [, int $outputSize = 32, string $key, bool $rawOutput = false ] )
```

* $str: The string to hash
* $outputSize: The length of the output hash (can be between 1 and 32)
* $key: Turns the output into a keyed hash using the specified key
* $rawOutput: If set to true, then the hash is returned in raw binary format

* Return value: A hex string containing the BLAKE2s hash of the input string

```php
string blake2_file ( string $filename [, bool $rawOutput = false ] )
```

* $filename: The filename of the file to hash
* $rawOutput: If set to true, then the hash is returned in raw binary format

* Return value: A hex string containing the BLAKE2 hash of the input file

```php
string b2sum ( string $filename [, bool $rawOutput = false ] )
```

is an alias to `blake2_file`


Examples
--------
```php
echo blake2('');
```

786a02f742015903c6c6fd852552d272912f4740e15847618a86e217f71f5419d25e1031afee585313896444934eb04b903a685b1448b755d56f701afe9be2ce

```php
echo blake2('Hello world', 20);
```

5ad31b81fc4dde5554e36af1e884d83ff5b24eb0

```php
echo blake2('Hello world', 20, 'foobar');
```

5b4bbc84b59ab5d9146089b143fd52f38638dcac


```php
echo blake2s('');
```

Outputs : 69217a3079908094e11121d042354a7c1f55b6482ca1a51e1b250dfd1ed0eef9

```php
echo b2sum('tests/sample.txt');
```

Outputs : a61b779ff667fbcc4775cbb02cd0763b9b5312fe6359a44a003f582ce6897c81a38a876122ce91dfec547d582fe269f6ea9bd291b60bccf95006dac10a4316f2

More Info
---------
https://github.com/BLAKE3-team/BLAKE3
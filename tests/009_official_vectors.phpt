--TEST--
blake3() matches the official BLAKE3 test vectors (hash, extended hash and keyed_hash)
--SKIPIF--
<?php
if (!extension_loaded('blake3')) die('skip blake3 extension required');
function blake3_find_vectors_file() {
    $candidates = [
        getenv('BLAKE3_VECTORS'),
        __DIR__ . '/blake3_vectors.json',
        'blake3_vectors.json',
        'tests/blake3_vectors.json',
    ];
    foreach ($candidates as $path) {
        if ($path !== false && $path !== '' && is_file($path)) {
            return $path;
        }
    }
    return false;
}
if (!blake3_find_vectors_file()) die('skip official vectors file not found');
?>
--FILE--
<?php
function blake3_find_vectors_file() {
    $candidates = [
        getenv('BLAKE3_VECTORS'),
        __DIR__ . '/blake3_vectors.json',
        'blake3_vectors.json',
        'tests/blake3_vectors.json',
    ];
    foreach ($candidates as $path) {
        if ($path !== false && $path !== '' && is_file($path)) {
            return $path;
        }
    }
    return false;
}

$vectorFile = blake3_find_vectors_file();
$vectors = json_decode(file_get_contents($vectorFile), true);
if (!is_array($vectors) || !isset($vectors['cases'], $vectors['key'])) {
    die("Unable to parse official vectors file: {$vectorFile}\n");
}
$key = $vectors['key'];

// Test vector inputs are filled with the repeating byte sequence 0..250 (251 bytes).
$pattern = '';
for ($i = 0; $i < 251; $i++) {
    $pattern .= chr($i);
}

$failures = 0;
$total = count($vectors['cases']);
foreach ($vectors['cases'] as $case) {
    $len = $case['input_len'];
    $input = substr(str_repeat($pattern, intdiv($len, 251) + 1), 0, $len);

    // Default 32-byte hash must equal the first 32 bytes of the extended vector.
    if (blake3($input) !== substr($case['hash'], 0, 64)) {
        printf("FAIL hash input_len=%d\n", $len);
        $failures++;
    }
    // Extended output: the official vectors use a 131-byte extended hash
    // (deliberately odd length), so request the full 131 bytes.
    if (blake3($input, 131) !== $case['hash']) {
        printf("FAIL extended hash input_len=%d\n", $len);
        $failures++;
    }
    // Keyed hash with the official test key, full 131-byte extended output.
    if (blake3($input, 131, $key) !== $case['keyed_hash']) {
        printf("FAIL keyed_hash input_len=%d\n", $len);
        $failures++;
    }
}

if ($failures === 0) {
    printf("All %d official vector cases passed (hash, extended hash, keyed hash).\n", $total);
} else {
    printf("%d of %d checks failed.\n", $failures, $total * 3);
}
--EXPECT--
All 35 official vector cases passed (hash, extended hash, keyed hash).

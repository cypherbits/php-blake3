<?php
// Minimal PHPT runner supporting --SKIPIF--, --FILE--, --EXPECT--
if ($argc < 2) {
    fwrite(STDERR, "Usage: php phpt_runner.php <testfile.phpt>\n");
    exit(1);
}
$testFile = $argv[1];
$contents = file_get_contents($testFile);
$sections = [];
$current = null;
foreach (preg_split('/\r?\n/', $contents) as $line) {
    if (preg_match('/^--([A-Z]+)--$/', $line, $m)) {
        $current = $m[1];
        $sections[$current] = '';
    } elseif ($current) {
        $sections[$current] .= $line . "\n";
    }
}
if (isset($sections['SKIPIF'])) {
    $code = $sections['SKIPIF'];
    $tmpSkip = tempnam(sys_get_temp_dir(), 'phpt_skip_') . '.php';
    file_put_contents($tmpSkip, $code);
    $cmd = escapeshellcmd(PHP_BINARY) . ' -d extension=blake3 ' . escapeshellarg($tmpSkip);
    $out = shell_exec($cmd);
    unlink($tmpSkip);
    if ($out !== null && preg_match('/^skip/i', trim($out))) {
        echo basename($testFile) . ": SKIPPED\n";
        exit(0);
    }
}
if (!isset($sections['FILE']) || !isset($sections['EXPECT'])) {
    fwrite(STDERR, "Missing required sections in $testFile\n");
    exit(1);
}
$code = $sections['FILE'];
$tmpFile = tempnam(sys_get_temp_dir(), 'phpt_') . '.php';
file_put_contents($tmpFile, $code);
$cmd = escapeshellcmd(PHP_BINARY) . ' -d extension=blake3 ' . escapeshellarg($tmpFile);
$actual = shell_exec($cmd);
unlink($tmpFile);
$expected = $sections['EXPECT'];
// Normalize trailing newlines
$actualNorm = rtrim($actual ?? '', "\r\n") . "\n";
$expectedNorm = rtrim($expected, "\r\n") . "\n";
if ($actualNorm === $expectedNorm) {
    echo basename($testFile) . ": PASS\n";
    exit(0);
} else {
    echo basename($testFile) . ": FAIL\n";
    echo "Expected:\n$expectedNorm\n";
    echo "Got:\n$actualNorm\n";
    exit(1);
}


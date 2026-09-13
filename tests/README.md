Run tests inside Ubuntu 24.04 container

Build and run the test container from repo root:

```bash
# Build the image
DOCKER_BUILDKIT=1 docker build -f tests/Dockerfile -t php-blake3-tests:ubuntu24 .

# Run tests (will compile the extension and execute PHPT)
docker run --rm -t php-blake3-tests:ubuntu24
```

Notes
- The Dockerfile installs PHP 8.5 from the ondrej/php PPA and uses the system run-tests.php shipped with the PHP headers (located dynamically, e.g. /usr/lib/php/20250925/build/run-tests.php).
- The extension is enabled for CLI via /etc/php/8.5/cli/conf.d/50-blake3.ini.
- 009_official_vectors.phpt validates all cases from the official BLAKE3 test vectors (tests/blake3_vectors.json); it skips automatically if that file is missing.
- You can override the CMD to run a single test:

```bash
docker run --rm -t php-blake3-tests:ubuntu24 bash -lc "php \$(find /usr/lib/php -name run-tests.php | head -n1) -q tests/001_empty_string.phpt"
```

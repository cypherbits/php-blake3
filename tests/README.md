Run tests inside Ubuntu 24.04 container

Build and run the test container from repo root:

```bash
# Build the image
DOCKER_BUILDKIT=1 docker build -f tests/Dockerfile -t php-blake3-tests:ubuntu24 .

# Run tests (will compile the extension and execute PHPT)
docker run --rm -t php-blake3-tests:ubuntu24
```

Notes
- The Dockerfile installs PHP 8.3 and uses the system run-tests.php located at /usr/lib/php/build/run-tests.php.
- The extension is enabled for CLI via /etc/php/8.3/cli/conf.d/50-blake3.ini.
- You can override the CMD to run a single test:

```bash
docker run --rm -t php-blake3-tests:ubuntu24 bash -lc "php -d extension=blake3 /usr/lib/php/build/run-tests.php -q tests/001_empty_string.phpt"
```




# syntax=docker/dockerfile:1
FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive

# Install PHP 8.5 (from ondrej/php PPA, not in stock Ubuntu 24.04),
# plus build tooling and dev headers to compile the extension and run PHPT
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       ca-certificates \
       software-properties-common \
       make gcc build-essential \
    && add-apt-repository -y ppa:ondrej/php \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
       php8.5 php8.5-cli php8.5-dev \
    && rm -rf /var/lib/apt/lists/*

# Copy the full repository into the container build context
WORKDIR /work
COPY . /work

# Build the extension
RUN phpize \
    && ./configure --enable-blake3 \
    && make -j"$(nproc)" \
    && make install

# Enable the extension once (avoid double load when using -d extension also)
RUN echo "extension=blake3.so" > /etc/php/8.5/cli/conf.d/50-blake3.ini \
    && php -m | grep -i blake3 || (echo "blake3 not loaded" && exit 1)

# Entry script: prefer system run-tests.php, fallback to custom runner
RUN printf '%s\n' \
  '#!/usr/bin/env bash' \
  'set -euo pipefail' \
  'RUN_TESTS="$(find /usr/lib/php -name run-tests.php | head -n1)"' \
  'if [ -n "${RUN_TESTS}" ]; then' \
  '  exec php "${RUN_TESTS}" -q --show-diff --show-slow 1000 --set-timeout 120 tests' \
  'else' \
  '  echo "run-tests.php not found, using custom runner"' \
  '  for f in tests/*.phpt; do PHPT_ADD_EXTENSION=1 php .github/phpt_runner.php "$f" || exit 1; done' \
  'fi' \
  > /usr/local/bin/run-phpt && chmod +x /usr/local/bin/run-phpt

# Default command runs the PHPT test-suite inside the container
CMD ["/usr/local/bin/run-phpt"]

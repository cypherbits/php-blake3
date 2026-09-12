#!/usr/bin/env bash
set -euo pipefail

PHP_VERSION="${PHP_VERSION:-8.5}"
IMAGE_TAG="blake3build:php${PHP_VERSION}"
CONTAINER_NAME="blake3build_container_php${PHP_VERSION}"

docker build --tag "${IMAGE_TAG}" .
docker rm -f "${CONTAINER_NAME}" 2>/dev/null || true
docker create --name "${CONTAINER_NAME}" "${IMAGE_TAG}"
#/making/modules/blake3.so
docker cp "${CONTAINER_NAME}:/making/modules/blake3.so" "./compiled/blake3-php${PHP_VERSION}.so"
docker rm "${CONTAINER_NAME}"

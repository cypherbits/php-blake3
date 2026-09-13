#!/usr/bin/env bash
set -euo pipefail

PHP_VERSION="${PHP_VERSION:-8.5}"
ARCH="${ARCH:-$(docker version --format '{{.Client.Arch}}')}"
IMAGE_TAG="blake3build:php${PHP_VERSION}-${ARCH}"
CONTAINER_NAME="blake3build_container_php${PHP_VERSION}_${ARCH}"
ARTIFACT="blake3-php${PHP_VERSION}-${ARCH}.so"

BUILD_ARGS=()
if [ "${ARCH}" != "amd64" ]; then
  BUILD_ARGS=(--platform "linux/${ARCH}")
fi

docker build --tag "${IMAGE_TAG}" "${BUILD_ARGS[@]}" .
docker rm -f "${CONTAINER_NAME}" 2>/dev/null || true
docker create --name "${CONTAINER_NAME}" "${IMAGE_TAG}"
#/making/modules/blake3.so
mkdir -p ./compiled
docker cp "${CONTAINER_NAME}:/making/modules/blake3.so" "./compiled/${ARTIFACT}"
docker rm "${CONTAINER_NAME}"

echo "Built ./compiled/${ARTIFACT}"

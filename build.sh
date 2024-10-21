docker build --tag "blake3build:latest" .
docker create --name blake3build_container blake3build
#/making/modules/blake3.so
docker cp blake3build_container:/making/modules/blake3.so ./compiled/blake3.so
docker rm blake3build_container
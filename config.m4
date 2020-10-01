PHP_ARG_ENABLE(blake2,
[Whether to enable BLAKE2 support],
[--enable-blake2           Enable BLAKE2 Extension])

if test "$PHP_BLAKE2" != "no"; then
    PHP_NEW_EXTENSION(blake2, php_blake2.c blake3.c blake3_dispatch.c blake3_portable.c blake3_sse2_x86-64_unix.S blake3_sse41_x86-64_unix.S blake3_avx2_x86-64_unix.S blake3_avx512_x86-64_unix.S, $ext_shared)
fi
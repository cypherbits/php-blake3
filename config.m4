PHP_ARG_ENABLE(blake3,
[Whether to enable BLAKE3 support],
[--enable-blake3           Enable BLAKE3 Extension])

if test "$PHP_BLAKE3" != "no"; then
    PHP_NEW_EXTENSION(blake3, php_blake3.c blake3.c blake3_dispatch.c blake3_portable.c blake3_sse2_x86-64_unix.S blake3_sse41_x86-64_unix.S blake3_avx2_x86-64_unix.S blake3_avx512_x86-64_unix.S, $ext_shared)
fi
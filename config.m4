PHP_ARG_ENABLE(blake3,
[Whether to enable BLAKE3 support],
[--enable-blake3           Enable BLAKE3 Extension])

if test "$PHP_BLAKE3" != "no"; then
    dnl Detect target CPU architecture
    AC_MSG_CHECKING([for blake3 architecture optimization])
    case "$host_cpu" in
      x86_64|amd64)
        AC_MSG_RESULT([x86_64 (enabling SSE/AVX assembly)])
        BLAKE3_ASM_SOURCES="blake3_sse2_x86-64_unix.S blake3_sse41_x86-64_unix.S blake3_avx2_x86-64_unix.S blake3_avx512_x86-64_unix.S"
        ;;
      *)
        AC_MSG_RESULT([$host_cpu (using portable C implementation)])
        BLAKE3_ASM_SOURCES=""
        ;;
    esac

    dnl Pass detected assembly sources to extension build
    PHP_NEW_EXTENSION(blake3, php_blake3.c blake3.c blake3_dispatch.c blake3_portable.c $BLAKE3_ASM_SOURCES, $ext_shared)
fi

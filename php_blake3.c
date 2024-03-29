#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "ext/standard/info.h"
#include "ext/hash/php_hash.h"
#include "blake3.h"
#include "php_blake3.h"

#define PHP_BLAKE3_NAME "BLAKE3"
#define PHP_BLAKE3_VERSION "0.1.0"

ZEND_BEGIN_ARG_INFO_EX(arginfo_void, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_blake3, 0, 0, 1)
	ZEND_ARG_INFO(0, str)
	ZEND_ARG_INFO(0, outputSize)
	ZEND_ARG_INFO(0, key)
    ZEND_ARG_INFO(0, rawOutput)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_blake3_file, 0, 0, 1)
	ZEND_ARG_INFO(0, filename)
    ZEND_ARG_INFO(0, rawOutput)
ZEND_END_ARG_INFO()

zend_function_entry blake3_functions[] = {
    PHP_FE(blake3, arginfo_blake3)
    PHP_FE(blake3_file, arginfo_blake3_file)
    {NULL, NULL, NULL}
};

PHP_MINIT_FUNCTION(blake3){
REGISTER_LONG_CONSTANT("BLAKE3_OUT_LEN",
                           BLAKE3_OUT_LEN, CONST_CS | CONST_PERSISTENT);
}

zend_module_entry blake3_module_entry = {
#if ZEND_MODULE_API_NO >= 20010901
    STANDARD_MODULE_HEADER,
#endif
    PHP_BLAKE3_NAME,
    blake3_functions,
    PHP_MINIT(blake3),
    NULL,
    NULL,
    NULL,
    NULL,
#if ZEND_MODULE_API_NO >= 20010901
    PHP_BLAKE3_VERSION,
#endif
    STANDARD_MODULE_PROPERTIES
};

#ifdef COMPILE_DL_BLAKE3
ZEND_GET_MODULE(blake3)
#endif

PHP_FUNCTION(blake3)
{
#if ZEND_MODULE_API_NO >= 20151012
    zend_long hashByteLength = BLAKE3_OUT_LEN;
    size_t dataByteLength;
    size_t keyLength = 0;
#else
    long hashByteLength = BLAKE3_OUT_LEN;
    int dataByteLength;
    int keyLength = 0;
#endif
    unsigned char *data;
    unsigned char *key;
    zend_bool rawOutput = 0;

    if (zend_parse_parameters(ZEND_NUM_ARGS(), "s|lsb", &data, &dataByteLength, &hashByteLength, &key, &keyLength, &rawOutput) == FAILURE) {
        return;
    }

    zend_bool hasError = 0;

    if (hashByteLength < 1) {
        hasError = 1;
        zend_error(E_ERROR, "BLAKE3 output length cannot be zero");
    }

    if (keyLength > 0 && keyLength != BLAKE3_KEY_LEN) {
        hasError = 1;
        zend_error(E_ERROR, "BLAKE3 key length MUST be 32 bytes");
    }

    if (hasError) {
        RETURN_FALSE;
    }

    char* hashOutput = (unsigned char*) emalloc(hashByteLength);

    int result = blake3(hashOutput, hashByteLength, data, dataByteLength, key, keyLength);

    if (result != 0) {
        zend_error(E_ERROR, "Error generating BLAKE3 hash");
        efree(hashOutput);
        RETURN_FALSE;
    }

    if (rawOutput) {
#if ZEND_MODULE_API_NO >= 20151012
         RETVAL_STRINGL(hashOutput, hashByteLength);
#else
         RETVAL_STRINGL(hashOutput, hashByteLength, 1);
#endif
    } else {
        char* hex = (char*) emalloc(hashByteLength * 2 + 1);
        php_hash_bin2hex(hex, (unsigned char *) hashOutput, hashByteLength);
        hex[hashByteLength * 2] = '\0';

#if ZEND_MODULE_API_NO >= 20151012
        RETVAL_STRING(hex);
#else
        RETVAL_STRING(hex,1);
#endif

        efree(hex);
    }

    efree(hashOutput);
}

PHP_FUNCTION(blake3_file)
{
#if ZEND_MODULE_API_NO >= 20151012
    zend_long hashByteLength = BLAKE3_OUT_LEN;
    size_t dataByteLength;
#else
    long hashByteLength = BLAKE3_OUT_LEN;
    int dataByteLength;
#endif

    char          *data;
    int           rawOutput = 0;

    php_stream    *stream;
    int           n;
    unsigned char buf[1024];

    blake3_hasher hasher;

    if (zend_parse_parameters(ZEND_NUM_ARGS(), "p|b", &data, &dataByteLength, &rawOutput) == FAILURE) {
        return;
    }

    stream = php_stream_open_wrapper(data, "rb", REPORT_ERRORS, NULL);
    if (!stream) {
        RETURN_FALSE;
    }

    char* hashOutput = (char*) emalloc(hashByteLength);

    blake3_hasher_init(&hasher);

    while ((n = php_stream_read(stream, buf, sizeof(buf))) > 0) {
        blake3_hasher_update(&hasher, (const uint8_t *)buf, n);
    }

    blake3_hasher_finalize(&hasher, hashOutput, hashByteLength);

    php_stream_close(stream);

    if (n<0) {
        efree(hashOutput);
        RETURN_FALSE;
    }

    if (rawOutput) {
#if ZEND_MODULE_API_NO >= 20151012
        RETVAL_STRINGL(hashOutput, hashByteLength);
#else
        RETVAL_STRINGL(hashOutput, hashByteLength, 1);
#endif
    } else {
        char* hex = (char*) emalloc(hashByteLength * 2 + 1);
        php_hash_bin2hex(hex, (unsigned char *) hashOutput, hashByteLength);
        hex[hashByteLength * 2] = '\0';
#if ZEND_MODULE_API_NO >= 20151012
        RETVAL_STRING(hex);
#else
        RETVAL_STRING(hex,1);
#endif
        efree(hex);
    }

    efree(hashOutput);
}

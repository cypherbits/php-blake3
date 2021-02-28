#ifndef PHP_BLAKE3_H
#define PHP_BLAKE3_H

PHP_FUNCTION(blake3);
PHP_FUNCTION(blake3_file);

extern zend_module_entry blake3_module_entry;
#define phpext_blake3_ptr &blake3_module_entry

#endif

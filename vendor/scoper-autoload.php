<?php

// scoper-autoload.php @generated by PhpScoper

// Backup the autoloaded Composer files
if (isset($GLOBALS['__composer_autoload_files'])) {
    $existingComposerAutoloadFiles = $GLOBALS['__composer_autoload_files'];
}

$loader = require_once __DIR__.'/autoload.php';
// Ensure InstalledVersions is available
$installedVersionsPath = __DIR__.'/composer/InstalledVersions.php';
if (file_exists($installedVersionsPath)) require_once $installedVersionsPath;

// Restore the backup
if (isset($existingComposerAutoloadFiles)) {
    $GLOBALS['__composer_autoload_files'] = $existingComposerAutoloadFiles;
} else {
    unset($GLOBALS['__composer_autoload_files']);
}

// Class aliases. For more information see:
// https://github.com/humbug/php-scoper/blob/master/docs/further-reading.md#class-aliases
if (!function_exists('humbug_phpscoper_expose_class')) {
    function humbug_phpscoper_expose_class(string $exposed, string $prefixed): void {
        if (!class_exists($exposed, false) && !interface_exists($exposed, false) && !trait_exists($exposed, false)) {
            spl_autoload_call($prefixed);
        }
    }
}
humbug_phpscoper_expose_class('CastToType', 'PrefixedByPoP\CastToType');
humbug_phpscoper_expose_class('Override', 'PrefixedByPoP\Override');
humbug_phpscoper_expose_class('DateInvalidOperationException', 'PrefixedByPoP\DateInvalidOperationException');
humbug_phpscoper_expose_class('DateMalformedStringException', 'PrefixedByPoP\DateMalformedStringException');
humbug_phpscoper_expose_class('DateRangeError', 'PrefixedByPoP\DateRangeError');
humbug_phpscoper_expose_class('DateException', 'PrefixedByPoP\DateException');
humbug_phpscoper_expose_class('DateMalformedPeriodStringException', 'PrefixedByPoP\DateMalformedPeriodStringException');
humbug_phpscoper_expose_class('DateObjectError', 'PrefixedByPoP\DateObjectError');
humbug_phpscoper_expose_class('DateInvalidTimeZoneException', 'PrefixedByPoP\DateInvalidTimeZoneException');
humbug_phpscoper_expose_class('DateMalformedIntervalStringException', 'PrefixedByPoP\DateMalformedIntervalStringException');
humbug_phpscoper_expose_class('DateError', 'PrefixedByPoP\DateError');
humbug_phpscoper_expose_class('ValueError', 'PrefixedByPoP\ValueError');
humbug_phpscoper_expose_class('PhpToken', 'PrefixedByPoP\PhpToken');
humbug_phpscoper_expose_class('Stringable', 'PrefixedByPoP\Stringable');
humbug_phpscoper_expose_class('UnhandledMatchError', 'PrefixedByPoP\UnhandledMatchError');
humbug_phpscoper_expose_class('Attribute', 'PrefixedByPoP\Attribute');
humbug_phpscoper_expose_class('�', 'PrefixedByPoP\�');
humbug_phpscoper_expose_class('JsonException', 'PrefixedByPoP\JsonException');
humbug_phpscoper_expose_class('ComposerAutoloaderInit3d8edb8edc351c19f3770eda0013efea', 'PrefixedByPoP\ComposerAutoloaderInit3d8edb8edc351c19f3770eda0013efea');

// Function aliases. For more information see:
// https://github.com/humbug/php-scoper/blob/master/docs/further-reading.md#function-aliases
if (!function_exists('add_action')) { function add_action() { return \PrefixedByPoP\add_action(...func_get_args()); } }
if (!function_exists('array_key_first')) { function array_key_first() { return \PrefixedByPoP\array_key_first(...func_get_args()); } }
if (!function_exists('array_key_last')) { function array_key_last() { return \PrefixedByPoP\array_key_last(...func_get_args()); } }
if (!function_exists('ctype_alnum')) { function ctype_alnum() { return \PrefixedByPoP\ctype_alnum(...func_get_args()); } }
if (!function_exists('ctype_alpha')) { function ctype_alpha() { return \PrefixedByPoP\ctype_alpha(...func_get_args()); } }
if (!function_exists('ctype_cntrl')) { function ctype_cntrl() { return \PrefixedByPoP\ctype_cntrl(...func_get_args()); } }
if (!function_exists('ctype_digit')) { function ctype_digit() { return \PrefixedByPoP\ctype_digit(...func_get_args()); } }
if (!function_exists('ctype_graph')) { function ctype_graph() { return \PrefixedByPoP\ctype_graph(...func_get_args()); } }
if (!function_exists('ctype_lower')) { function ctype_lower() { return \PrefixedByPoP\ctype_lower(...func_get_args()); } }
if (!function_exists('ctype_print')) { function ctype_print() { return \PrefixedByPoP\ctype_print(...func_get_args()); } }
if (!function_exists('ctype_punct')) { function ctype_punct() { return \PrefixedByPoP\ctype_punct(...func_get_args()); } }
if (!function_exists('ctype_space')) { function ctype_space() { return \PrefixedByPoP\ctype_space(...func_get_args()); } }
if (!function_exists('ctype_upper')) { function ctype_upper() { return \PrefixedByPoP\ctype_upper(...func_get_args()); } }
if (!function_exists('ctype_xdigit')) { function ctype_xdigit() { return \PrefixedByPoP\ctype_xdigit(...func_get_args()); } }
if (!function_exists('fdiv')) { function fdiv() { return \PrefixedByPoP\fdiv(...func_get_args()); } }
if (!function_exists('get_debug_type')) { function get_debug_type() { return \PrefixedByPoP\get_debug_type(...func_get_args()); } }
if (!function_exists('get_mangled_object_vars')) { function get_mangled_object_vars() { return \PrefixedByPoP\get_mangled_object_vars(...func_get_args()); } }
if (!function_exists('get_resource_id')) { function get_resource_id() { return \PrefixedByPoP\get_resource_id(...func_get_args()); } }
if (!function_exists('getallheaders')) { function getallheaders() { return \PrefixedByPoP\getallheaders(...func_get_args()); } }
if (!function_exists('headers_send')) { function headers_send() { return \PrefixedByPoP\headers_send(...func_get_args()); } }
if (!function_exists('hrtime')) { function hrtime() { return \PrefixedByPoP\hrtime(...func_get_args()); } }
if (!function_exists('includeIfExists')) { function includeIfExists() { return \PrefixedByPoP\includeIfExists(...func_get_args()); } }
if (!function_exists('is_countable')) { function is_countable() { return \PrefixedByPoP\is_countable(...func_get_args()); } }
if (!function_exists('json_validate')) { function json_validate() { return \PrefixedByPoP\json_validate(...func_get_args()); } }
if (!function_exists('ldap_connect_wallet')) { function ldap_connect_wallet() { return \PrefixedByPoP\ldap_connect_wallet(...func_get_args()); } }
if (!function_exists('ldap_exop_sync')) { function ldap_exop_sync() { return \PrefixedByPoP\ldap_exop_sync(...func_get_args()); } }
if (!function_exists('litespeed_finish_request')) { function litespeed_finish_request() { return \PrefixedByPoP\litespeed_finish_request(...func_get_args()); } }
if (!function_exists('mb_check_encoding')) { function mb_check_encoding() { return \PrefixedByPoP\mb_check_encoding(...func_get_args()); } }
if (!function_exists('mb_chr')) { function mb_chr() { return \PrefixedByPoP\mb_chr(...func_get_args()); } }
if (!function_exists('mb_convert_case')) { function mb_convert_case() { return \PrefixedByPoP\mb_convert_case(...func_get_args()); } }
if (!function_exists('mb_convert_encoding')) { function mb_convert_encoding() { return \PrefixedByPoP\mb_convert_encoding(...func_get_args()); } }
if (!function_exists('mb_convert_variables')) { function mb_convert_variables() { return \PrefixedByPoP\mb_convert_variables(...func_get_args()); } }
if (!function_exists('mb_decode_mimeheader')) { function mb_decode_mimeheader() { return \PrefixedByPoP\mb_decode_mimeheader(...func_get_args()); } }
if (!function_exists('mb_decode_numericentity')) { function mb_decode_numericentity() { return \PrefixedByPoP\mb_decode_numericentity(...func_get_args()); } }
if (!function_exists('mb_detect_encoding')) { function mb_detect_encoding() { return \PrefixedByPoP\mb_detect_encoding(...func_get_args()); } }
if (!function_exists('mb_detect_order')) { function mb_detect_order() { return \PrefixedByPoP\mb_detect_order(...func_get_args()); } }
if (!function_exists('mb_encode_mimeheader')) { function mb_encode_mimeheader() { return \PrefixedByPoP\mb_encode_mimeheader(...func_get_args()); } }
if (!function_exists('mb_encode_numericentity')) { function mb_encode_numericentity() { return \PrefixedByPoP\mb_encode_numericentity(...func_get_args()); } }
if (!function_exists('mb_encoding_aliases')) { function mb_encoding_aliases() { return \PrefixedByPoP\mb_encoding_aliases(...func_get_args()); } }
if (!function_exists('mb_get_info')) { function mb_get_info() { return \PrefixedByPoP\mb_get_info(...func_get_args()); } }
if (!function_exists('mb_http_input')) { function mb_http_input() { return \PrefixedByPoP\mb_http_input(...func_get_args()); } }
if (!function_exists('mb_http_output')) { function mb_http_output() { return \PrefixedByPoP\mb_http_output(...func_get_args()); } }
if (!function_exists('mb_internal_encoding')) { function mb_internal_encoding() { return \PrefixedByPoP\mb_internal_encoding(...func_get_args()); } }
if (!function_exists('mb_language')) { function mb_language() { return \PrefixedByPoP\mb_language(...func_get_args()); } }
if (!function_exists('mb_list_encodings')) { function mb_list_encodings() { return \PrefixedByPoP\mb_list_encodings(...func_get_args()); } }
if (!function_exists('mb_ord')) { function mb_ord() { return \PrefixedByPoP\mb_ord(...func_get_args()); } }
if (!function_exists('mb_output_handler')) { function mb_output_handler() { return \PrefixedByPoP\mb_output_handler(...func_get_args()); } }
if (!function_exists('mb_parse_str')) { function mb_parse_str() { return \PrefixedByPoP\mb_parse_str(...func_get_args()); } }
if (!function_exists('mb_scrub')) { function mb_scrub() { return \PrefixedByPoP\mb_scrub(...func_get_args()); } }
if (!function_exists('mb_str_pad')) { function mb_str_pad() { return \PrefixedByPoP\mb_str_pad(...func_get_args()); } }
if (!function_exists('mb_str_split')) { function mb_str_split() { return \PrefixedByPoP\mb_str_split(...func_get_args()); } }
if (!function_exists('mb_stripos')) { function mb_stripos() { return \PrefixedByPoP\mb_stripos(...func_get_args()); } }
if (!function_exists('mb_stristr')) { function mb_stristr() { return \PrefixedByPoP\mb_stristr(...func_get_args()); } }
if (!function_exists('mb_strlen')) { function mb_strlen() { return \PrefixedByPoP\mb_strlen(...func_get_args()); } }
if (!function_exists('mb_strpos')) { function mb_strpos() { return \PrefixedByPoP\mb_strpos(...func_get_args()); } }
if (!function_exists('mb_strrchr')) { function mb_strrchr() { return \PrefixedByPoP\mb_strrchr(...func_get_args()); } }
if (!function_exists('mb_strrichr')) { function mb_strrichr() { return \PrefixedByPoP\mb_strrichr(...func_get_args()); } }
if (!function_exists('mb_strripos')) { function mb_strripos() { return \PrefixedByPoP\mb_strripos(...func_get_args()); } }
if (!function_exists('mb_strrpos')) { function mb_strrpos() { return \PrefixedByPoP\mb_strrpos(...func_get_args()); } }
if (!function_exists('mb_strstr')) { function mb_strstr() { return \PrefixedByPoP\mb_strstr(...func_get_args()); } }
if (!function_exists('mb_strtolower')) { function mb_strtolower() { return \PrefixedByPoP\mb_strtolower(...func_get_args()); } }
if (!function_exists('mb_strtoupper')) { function mb_strtoupper() { return \PrefixedByPoP\mb_strtoupper(...func_get_args()); } }
if (!function_exists('mb_strwidth')) { function mb_strwidth() { return \PrefixedByPoP\mb_strwidth(...func_get_args()); } }
if (!function_exists('mb_substitute_character')) { function mb_substitute_character() { return \PrefixedByPoP\mb_substitute_character(...func_get_args()); } }
if (!function_exists('mb_substr')) { function mb_substr() { return \PrefixedByPoP\mb_substr(...func_get_args()); } }
if (!function_exists('mb_substr_count')) { function mb_substr_count() { return \PrefixedByPoP\mb_substr_count(...func_get_args()); } }
if (!function_exists('password_algos')) { function password_algos() { return \PrefixedByPoP\password_algos(...func_get_args()); } }
if (!function_exists('preg_last_error_msg')) { function preg_last_error_msg() { return \PrefixedByPoP\preg_last_error_msg(...func_get_args()); } }
if (!function_exists('sapi_windows_vt100_support')) { function sapi_windows_vt100_support() { return \PrefixedByPoP\sapi_windows_vt100_support(...func_get_args()); } }
if (!function_exists('spl_object_id')) { function spl_object_id() { return \PrefixedByPoP\spl_object_id(...func_get_args()); } }
if (!function_exists('str_contains')) { function str_contains() { return \PrefixedByPoP\str_contains(...func_get_args()); } }
if (!function_exists('str_ends_with')) { function str_ends_with() { return \PrefixedByPoP\str_ends_with(...func_get_args()); } }
if (!function_exists('str_starts_with')) { function str_starts_with() { return \PrefixedByPoP\str_starts_with(...func_get_args()); } }
if (!function_exists('stream_context_set_options')) { function stream_context_set_options() { return \PrefixedByPoP\stream_context_set_options(...func_get_args()); } }
if (!function_exists('stream_isatty')) { function stream_isatty() { return \PrefixedByPoP\stream_isatty(...func_get_args()); } }
if (!function_exists('utf8_decode')) { function utf8_decode() { return \PrefixedByPoP\utf8_decode(...func_get_args()); } }
if (!function_exists('utf8_encode')) { function utf8_encode() { return \PrefixedByPoP\utf8_encode(...func_get_args()); } }

return $loader;

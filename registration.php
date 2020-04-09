<?php

/*
 * Copyright (c) 2020 Danny Damsky
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. Neither the name of the author nor the names of its contributors may
 *    be used to endorse or promote products derived from this software
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 */

/**
 * registration.php
 *
 * A collection of utilities meant to improve the experience of developing modules for Magento.
 *
 * @since 2020-03-24
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */

use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

if (!function_exists('booltostr')) {
    if (!defined('BOOL_TO_STR_OPTION_LOWERCASE')) {
        define('BOOL_TO_STR_OPTION_LOWERCASE', 0);
    }
    if (!defined('BOOL_TO_STR_OPTION_UPPERCASE')) {
        define('BOOL_TO_STR_OPTION_UPPERCASE', 1);
    }
    if (!defined('BOOL_TO_STR_OPTION_CAPITALIZE')) {
        define('BOOL_TO_STR_OPTION_CAPITALIZE', 2);
    }
    if (!defined('BOOL_TO_STR_OPTIONS_MAP')) {
        define('BOOL_TO_STR_OPTIONS_MAP', [
            BOOL_TO_STR_OPTION_LOWERCASE => [
                false => 'false',
                true => 'true'
            ],
            BOOL_TO_STR_OPTION_UPPERCASE => [
                false => 'FALSE',
                true => 'TRUE'
            ],
            BOOL_TO_STR_OPTION_CAPITALIZE => [
                false => 'False',
                true => 'True'
            ]
        ]);
    }
    /**
     * Convert a boolean to its string representation.
     *
     * @param bool $bool
     * @param int $mode The mode in which to return the boolean string.
     * @return string
     * @see BOOL_TO_STR_OPTION_LOWERCASE
     * @see BOOL_TO_STR_OPTION_UPPERCASE
     * @see BOOL_TO_STR_OPTION_CAPITALIZE
     */
    function booltostr(bool $bool, int $mode = BOOL_TO_STR_OPTION_LOWERCASE): string
    {
        return BOOL_TO_STR_OPTIONS_MAP[$mode][$bool];
    }
}

if (!function_exists('prettify')) {
    /**
     * Returns a "pretty" version of a given text.
     * All words are capitalized and spaces are added.
     *
     * Examples:
     * Color_code => Color Code
     * helloWorld => Hello World
     * Hello-world => Hello World
     *
     * @param string $text
     * @return string
     */
    function prettify(string $text): string
    {
        return preg_replace('/([a-z])([A-Z])/', '$1 $2', str_replace(['_', '-'], ' ', ucwords($text, "_- \t\r\n\f\v")));
    }
}

if (!function_exists('time_millis')) {
    /**
     * Returns the time in milliseconds.
     *
     * @return int
     */
    function time_millis(): int
    {
        return (int)round(microtime(true) * 1000);
    }
}

if (!function_exists('escape_double_quotes_in_json_string')) {
    /**
     * Fixes the double quotes issue in some JSONs.
     *
     * @param string $jsonString
     * @param string $replacement
     * @return string
     */
    function escape_double_quotes_in_json_string(string $jsonString, string $replacement = "''"): string
    {
        return preg_replace_callback(
            '/(?<!\\\\)(?:\\\\{2})*\\\\(?!\\\\)"/',
            static function (array $match) use ($replacement): string {
                return str_replace('\\"', $replacement, $match[0]);
            },
            $jsonString
        );
    }
}

if (!function_exists('convert_to_utf8')) {
    /**
     * Converts a given unicode string to UTF-8.
     *
     * @param string $str
     * @return string
     */
    function convert_to_utf8(string $str): string
    {
        return preg_replace_callback(
            '/\\\\u([0-9a-fA-F]{4})/',
            static function (array $match): string {
                return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
            },
            $str
        );
    }
}

if (!function_exists('compress_image_for_web')) {
    /**
     * Compress the image for web according to the following rules:
     *
     * 1. If the image doesn't have an alpha channel, use 85% quality JPEG compression.
     * 2. If the highest image size dimension parameter exceeds 1920 pixels, resize
     * the image by a multiplier of 1920 divided by that parameter.
     *
     * @param string $binaryImage
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $jpegCompressionQuality
     * @return array [String Image Blob, Boolean Image Has No Alpha Channel]
     * @throws ImagickException
     */
    function compress_image_for_web(
        string $binaryImage,
        int $maxWidth = 1920,
        int $maxHeight = 1920,
        int $jpegCompressionQuality = 85
    ): array
    {
        $image = new Imagick();
        $image->readImageBlob($binaryImage);
        $image->optimizeImageLayers();
        $image->stripImage();
        $noAlpha = $image->getImageAlphaChannel() === 0;
        $format = $image->getImageFormat();
        if ($noAlpha === true) {
            if ($format !== 'jpg' && $format !== 'jpeg') {
                $image->setImageFormat('jpg');
            }
            $image->setImageCompression(Imagick::COMPRESSION_JPEG);
            $image->setImageCompressionQuality($jpegCompressionQuality);
            $image->setInterlaceScheme(Imagick::INTERLACE_JPEG);
            $image->setSamplingFactors(['2x2', '1x1', '1x1']);
        } else if ($format === 'png') {
            $image->setOption('png:compression-level', 9);
            $image->setInterlaceScheme(Imagick::INTERLACE_PNG);
        } else if ($format === 'gif') {
            $image->setInterlaceScheme(Imagick::INTERLACE_GIF);
        }
        $width = $image->getImageWidth();
        $height = $image->getImageHeight();
        if ($width > $height && $width > $maxWidth) {
            $multiplier = $maxWidth / $width;
        } else if ($height >= $width && $height > $maxHeight) {
            $multiplier = $maxHeight / $height;
        } else {
            $multiplier = 1;
        }
        $image->adaptiveResizeImage($width * $multiplier, $height * $multiplier);
        $arr = [$image->getImageBlob(), $noAlpha];
        $image->clear();
        $image->destroy();
        return $arr;
    }
}

if (!function_exists('optimize_image_basic')) {
    /**
     * Get an optimized image from the given binary data.
     *
     * @param string $binaryImage
     * @param int $jpegCompressionQuality
     * @return string
     * @throws ImagickException
     */
    function optimize_image_basic(string $binaryImage, int $jpegCompressionQuality = 95): string
    {
        $image = new Imagick();
        $image->readImageBlob($binaryImage);
        $image->optimizeImageLayers();
        $image->stripImage();
        $format = $image->getImageFormat();
        if ($format === 'jpg' || $format === 'jpeg') {
            $image->setImageCompression(Imagick::COMPRESSION_JPEG);
            if ($image->getImageCompressionQuality() > $jpegCompressionQuality) {
                $image->setImageCompressionQuality($jpegCompressionQuality);
            }
            $image->setInterlaceScheme(Imagick::INTERLACE_JPEG);
        } else if ($format === 'png') {
            $image->setOption('png:compression-level', 9);
            $image->setInterlaceScheme(Imagick::INTERLACE_PNG);
        } else if ($format === 'gif') {
            $image->setInterlaceScheme(Imagick::INTERLACE_GIF);
        }
        $blob = $image->getImageBlob();
        $image->clear();
        $image->destroy();
        return $blob;
    }
}

if (!function_exists('get_trace_as_string')) {
    /**
     * Same as {@see Throwable::getTraceAsString()} but
     * does not truncate the output.
     *
     * @param Throwable $t
     * @param string $frameSeparator
     * @return string
     */
    function get_trace_as_string(Throwable $t, string $frameSeparator = "\n"): string
    {
        $rtn = '';
        $count = null;
        foreach ($t->getTrace() as $count => $frame) {
            $args = '';
            if (isset($frame['args'])) {
                foreach ($frame['args'] as $arg) {
                    if (is_string($arg)) {
                        $args .= "'$arg', ";
                    } elseif (is_array($arg)) {
                        $args .= 'Array, ';
                    } elseif ($arg === null) {
                        $args .= 'NULL, ';
                    } elseif (is_bool($arg)) {
                        $args .= ($arg) ? 'true, ' : 'false, ';
                    } elseif (is_object($arg)) {
                        $args .= 'Object(' . get_class($arg) . '), ';
                    } elseif (is_resource($arg)) {
                        $args .= get_resource_type($arg) . ', ';
                    } else {
                        $args .= "$arg, ";
                    }
                }
            }
            $current_file = $frame['file'] ?? '[internal function]';
            if (isset($frame['line'])) {
                $current_line = "({$frame['line']})";
            } else {
                $current_line = '';
            }
            $function_str = '';
            if (isset($frame['class'])) {
                $function_str .= $frame['class'];
            }
            if (isset($frame['type'])) {
                $function_str .= $frame['type'];
            }
            $function_str .= $frame['function'];
            $args = rtrim($args, ', ');
            $rtn .= "#{$count} {$current_file}{$current_line}: {$function_str}({$args}){$frameSeparator}";
        }
        ++$count;
        return "{$rtn}#{$count} {main}";
    }
}

if (!function_exists('str_shorten')) {
    /**
     * Shorten the given string with the given data.
     *
     * @param string $string
     * @param int $limit Maximum amount of characters.
     * @param string $break The character to break at.
     * @param string $pad The padding.
     * @return string
     */
    function str_shorten(string $string, int $limit, string $break = '.', string $pad = '…'): string
    {
        $strlen = strlen($string);
        // return with no change if string is shorter than $limit
        if ($strlen <= $limit) {
            return $string;
        }
        // is $break present between $limit and the end of the string?
        if (
            ($breakpoint = strpos($string, $break, $limit)) !== false &&
            $breakpoint < $strlen - 1
        ) {
            $string = substr($string, 0, $breakpoint) . $pad;
        }
        return $string;
    }
}

if (!function_exists('is_associative')) {
    /**
     * Return whether the given array is associative.
     *
     * @param array $arr
     * @return bool
     */
    function is_associative(array $arr): bool
    {
        return $arr !== [] && array_keys($arr) !== range(0, count($arr) - 1);
    }
}

if (!function_exists('str_contains')) {
    /**
     * Check whether a string (the haystack) contains
     * another string (the needle).
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function str_contains(string $haystack, string $needle): bool
    {
        return '' === $needle || false !== strpos($haystack, $needle);
    }
}

if (!function_exists('str_icontains')) {
    /**
     * Check whether a string (the haystack) contains
     * another string (the needle). (Case-insensitive variant)
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function str_icontains(string $haystack, string $needle): bool
    {
        return '' === $needle || false !== stripos($haystack, $needle);
    }
}

if (!function_exists('mb_str_contains')) {
    /**
     * Check whether a string (the haystack) contains
     * another string (the needle). (Multi-byte variant)
     *
     * @param string $haystack
     * @param string $needle
     * @param string|null $encoding
     * @return bool
     */
    function mb_str_contains(string $haystack, string $needle, ?string $encoding = null): bool
    {
        return '' === $needle || false !== mb_strpos($haystack, $needle, $encoding);
    }
}

if (!function_exists('mb_str_icontains')) {
    /**
     * Check whether a string (the haystack) contains
     * another string (the needle). (Multi-byte case-insensitive variant)
     *
     * @param string $haystack
     * @param string $needle
     * @param string|null $encoding
     * @return bool
     */
    function mb_str_icontains(string $haystack, string $needle, ?string $encoding = null): bool
    {
        return '' === $needle || false !== mb_stripos($haystack, $needle, 0, $encoding);
    }
}

if (!function_exists('get_module_name')) {
    if (!defined('GET_MODULE_NAME_OPTION_NORMAL')) {
        define('GET_MODULE_NAME_OPTION_NORMAL', 0);
    }
    if (!defined('GET_MODULE_NAME_OPTION_LOWERCASE')) {
        define('GET_MODULE_NAME_OPTION_LOWERCASE', 1);
    }
    if (!defined('GET_MODULE_NAME_OPTION_UPPERCASE')) {
        define('GET_MODULE_NAME_OPTION_UPPERCASE', 2);
    }
    /**
     * Get the module name for the given class name.
     *
     * @param string $className
     * @param string $format
     * @param int $returnMode
     * @return string
     *
     * @see GET_MODULE_NAME_OPTION_NORMAL - Return the string as-is
     * @see GET_MODULE_NAME_OPTION_LOWERCASE - Return the string in lowercase
     * @see GET_MODULE_NAME_OPTION_UPPERCASE - Returns the string in upperase
     */
    function get_module_name(string $className, string $format = '%s_%s', int $returnMode = GET_MODULE_NAME_OPTION_NORMAL): string
    {
        [$vendorName, $moduleName] = explode('\\', $className, 3);
        $returnString = sprintf($format, $vendorName, $moduleName);
        switch ($returnMode) {
            case GET_MODULE_NAME_OPTION_LOWERCASE:
                return strtolower($returnString);
            case GET_MODULE_NAME_OPTION_UPPERCASE:
                return strtoupper($returnString);
            case GET_MODULE_NAME_OPTION_NORMAL:
            default:
                return $returnString;
        }
    }
}

if (!function_exists('rglob')) {
    /**
     * Find pathnames matching a pattern (recursive version of the {@see glob()} function.
     * @param string $pattern <p>
     * The pattern. No tilde expansion or parameter substitution is done.
     * </p>
     * @param int $flags [optional] <p>
     * Valid flags:
     * GLOB_MARK - Adds a slash to each directory returned
     * GLOB_NOSORT - Return files as they appear in the directory (no sorting). When this flag is not used, the pathnames are sorted alphabetically
     * GLOB_NOCHECK - Return the search pattern if no files matching it were found
     * GLOB_NOESCAPE - Backslashes do not quote metacharacters
     * GLOB_BRACE - Not supported by this function
     * GLOB_ONLYDIR - Return only directory entries which match the pattern
     * GLOB_ERR - Stop on read errors (like unreadable directories), by default errors are ignored.
     * @return array|false an array containing the matched files/directories, an empty array
     * if no file matched or false on error.
     * </p>
     * <p>
     * On some systems it is impossible to distinguish between empty match and an
     * error.
     */
    function rglob(string $pattern, int $flags = 0)
    {
        $files = glob($pattern, $flags);
        $basePatternName = basename($pattern);
        $dsp = DIRECTORY_SEPARATOR;
        $patternDirname = dirname($pattern);
        foreach (glob("{$patternDirname}{$dsp}*", GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            foreach (rglob("{$dir}{$dsp}{$basePatternName}", $flags) as $innerFile) {
                $files[] = $innerFile;
            }
        }
        return $files;
    }
}

if (!function_exists('gzcompress_file')) {
    /**
     * GZIPs a file on disk (appending .gz to the name)
     *
     * @param string $source Path to file that should be compressed
     * @param integer $level GZIP compression level (default: 9)
     * @return bool If the operation was successful.
     */
    function gzcompress_file(string $source, int $level = 9): bool
    {
        $dest = "{$source}.gz";
        $mode = "wb{$level}";
        $error = false;
        if ($fp_out = gzopen($dest, $mode)) {
            if ($fp_in = fopen($source, 'rb')) {
                $bytes = 524288; // 512 KB
                while (!feof($fp_in)) {
                    gzwrite($fp_out, fread($fp_in, $bytes));
                }
                fclose($fp_in);
            } else {
                $error = true;
            }
            gzclose($fp_out);
        } else {
            $error = true;
        }
        if (!$error) {
            unlink($source);
        }
        return !$error;
    }
}

if (!function_exists('rm_rf')) {
    /**
     * Recursive delete function.
     *
     * @param string $target
     * @return bool
     */
    function rm_rf(string $target): bool
    {
        if (is_file($target)) {
            return unlink($target);
        }
        if (!is_dir($target)) {
            return false;
        }
        $files = glob("{$target}*", GLOB_NOSORT | GLOB_MARK);
        foreach ($files as $file) {
            rm_rf($file);
        }
        return rmdir($target);
    }
}

if (!function_exists('run_in_transaction')) {
    /**
     * Executes the specified callable in a database transaction.
     *
     * @param AbstractDb|AdapterInterface $connection Any class which can be used to begin/commit/rollback
     * transactions using the beginTransaction(), commit() and rollback() methods.
     * @param callable $operation The operation to execute in a single transaction.
     * @param bool $retryWithoutTransactionOnFail Whether to attempt to retry the operation the BEGIN TRANSACTION statement.
     * @return mixed Returns the result from the operation.
     * @throws Throwable Throws the exception from the operation.
     */
    function run_in_transaction($connection, callable $operation, bool $retryWithoutTransactionOnFail = false)
    {
        try {
            $connection->beginTransaction();
            $operationResult = $operation();
            $connection->commit();
            return $operationResult;
        } catch (Throwable $e) {
            $connection->rollBack();
            if ($retryWithoutTransactionOnFail) {
                return $operation();
            }
            throw $e;
        }
    }
}

/**
 * Register a module with the given module name and
 * the given module path.
 *
 * @param string $moduleName
 * @param string $modulePath
 */
$DannyDamsky_DevTools_Register = static function (string $moduleName, string $modulePath): void {
    ComponentRegistrar::register(
        ComponentRegistrar::MODULE,
        $moduleName,
        $modulePath
    );
};

/**
 * Enables automatic registration of modules in the
 * app/code and vendor directories that do not have a registration.php file.
 * (Allows modules to not have to include that file in order to work)
 */
$DannyDamsky_DevTools_RegisterModules = static function () use ($DannyDamsky_DevTools_Register): void {
    $DannyDamsky_DevTools_Register('DannyDamsky_DevTools', __DIR__);

    $dsp = DIRECTORY_SEPARATOR;

    /*
     * Retrieve application base directory.
     */
    $baseDir = __DIR__;
    if (strpos($baseDir, 'vendor') !== false) {
        $baseDir = dirname($baseDir, 3);
    } else if (strpos($baseDir, "app{$dsp}code") !== false) {
        $baseDir = dirname($baseDir, 4);
    }

    /*
     * Iterate over folders in app/code and register modules.
     */
    foreach (glob("$baseDir{$dsp}app{$dsp}code{$dsp}*{$dsp}*", GLOB_NOSORT | GLOB_ONLYDIR) as $modulePath) {
        if (is_dir($modulePath) && !file_exists("$modulePath{$dsp}registration.php")) {
            try {
                $paths = explode($dsp, $modulePath);
                [$moduleName, $vendorName] = [array_pop($paths), array_pop($paths)];
                $fullName = "${vendorName}_${moduleName}";
                $DannyDamsky_DevTools_Register($fullName, $modulePath);
            } catch (Throwable $e) {
                echo get_trace_as_string($e);
            }
        }
    }

    /*
     * Iterate over vendor folders and register vendor modules.
     */
    foreach (glob("$baseDir{$dsp}vendor{$dsp}*{$dsp}*", GLOB_NOSORT | GLOB_ONLYDIR) as $modulePath) {
        if (is_dir($modulePath) && !file_exists("$modulePath{$dsp}registration.php") && file_exists("$modulePath{$dsp}etc{$dsp}module.xml")) {
            try {
                $moduleXml = simplexml_load_string(file_get_contents("$modulePath{$dsp}etc{$dsp}module.xml"));
                /** @noinspection PhpUndefinedFieldInspection */
                $moduleNameXmlArray = (array)($moduleXml->module->attributes()->name);
                if (isset($moduleNameXmlArray[0])) {
                    $fullName = $moduleNameXmlArray[0];
                    $DannyDamsky_DevTools_Register($fullName, $modulePath);
                }
            } catch (Throwable $e) {
                echo get_trace_as_string($e);
            }
        }
    }
};

$DannyDamsky_DevTools_RegisterModules();

unset($DannyDamsky_DevTools_Register, $DannyDamsky_DevTools_RegisterModules);

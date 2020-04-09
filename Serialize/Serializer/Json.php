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

namespace DannyDamsky\DevTools\Serialize\Serializer;

use Magento\Framework\Serialize\Serializer\Json as MageJson;
use function array_walk_recursive;
use function convert_to_utf8;
use function escape_double_quotes_in_json_string;
use function is_array;
use function is_string;
use function json_decode;
use function json_last_error;
use function str_replace;
use function trim;

/**
 * Class Json
 * @package DannyDamsky\DevTools\Serialize\Serializer
 * @since 2020-03-26
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
class Json extends MageJson
{
    /**
     * Code used for escaping double quotes before parsing the JSON.
     *
     * @var string
     */
    protected const DOUBLE_QUOTE_ESCAPE_KEY = '#DOUBLE_QUOTE_ESCAPE_KEY#';

    /**
     * Code used for escaping forward slashes before parsing the JSON.
     *
     * @var string
     */
    protected const FORWARD_SLASH_ESCAPE_KEY = '#FORWARD_SLASH_ESCAPE_KEY#';

    /**
     * Code used for escaping backward slashes before parsing the JSON.
     *
     * @var string
     */
    protected const BACKWARD_SLASH_ESCAPE_KEY = '#BACKWARD_SLASH_ESCAPE_KEY#';

    /**
     * Code used for escaping single backward slashes before parsing the JSON.
     *
     * @var string
     */
    protected const SINGLE_BACKWARD_SLASH_ESCAPE_KEY = '#SINGLE_BACKWARD_SLASH_ESCAPE_KEY#';

    /**
     * Code used for escaping new lines before parsing the JSON.
     *
     * @var string
     */
    protected const NEWLINE_ESCAPE_KEY = '#NEWLINE_ESCAPE_KEY#';

    /**
     * Code used for escaping unescaped new lines before parsing the JSON.
     *
     * @var string
     */
    protected const UNESCAPED_NEWLINE_ESCAPE_KEY = '#UNESCAPED_NEWLINE_ESCAPE_KEY#';

    /**
     * Dangerous character searches.
     *
     * @var string[]
     */
    protected const DANGEROUS_SEARCHES = [
        '\\\\',
        '\\',
        '\n',
        "\n"
    ];

    /**
     * Dangerous character replacements.
     *
     * @var string[]
     */
    protected const DANGEROUS_REPLACEMENTS = [
        self::BACKWARD_SLASH_ESCAPE_KEY,
        self::SINGLE_BACKWARD_SLASH_ESCAPE_KEY,
        self::UNESCAPED_NEWLINE_ESCAPE_KEY,
        self::NEWLINE_ESCAPE_KEY
    ];

    /**
     * Searches for escaped keys.
     *
     * @var string[]
     */
    protected const ESCAPE_SEARCHES = [
        self::DOUBLE_QUOTE_ESCAPE_KEY,
        self::FORWARD_SLASH_ESCAPE_KEY,
        self::BACKWARD_SLASH_ESCAPE_KEY,
        self::SINGLE_BACKWARD_SLASH_ESCAPE_KEY,
        self::NEWLINE_ESCAPE_KEY,
        self::UNESCAPED_NEWLINE_ESCAPE_KEY
    ];

    /**
     * Replacements for escaped keys.
     *
     * @var string[]
     */
    protected const ESCAPE_REPLACEMENTS = [
        '"',
        '/',
        '\\\\',
        '\\',
        "\n",
        '\n'
    ];

    /**
     * @inheritDoc
     * @noinspection JsonEncodingApiUsageInspection
     */
    public function unserialize($string)
    {
        $modifiedText = trim($string);
        $json = json_decode($modifiedText, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $json;
        }
        $modifiedText = str_replace('\/', self::FORWARD_SLASH_ESCAPE_KEY, $modifiedText);
        $modifiedText = escape_double_quotes_in_json_string($modifiedText, self::DOUBLE_QUOTE_ESCAPE_KEY);
        $modifiedText = convert_to_utf8($modifiedText);
        $modifiedText = str_replace(self::DANGEROUS_SEARCHES, self::DANGEROUS_REPLACEMENTS, $modifiedText);
        $json = parent::unserialize($modifiedText);
        if (is_array($json)) {
            array_walk_recursive($json, static function (&$item): void {
                if (is_string($item) && !empty($item)) {
                    $item = str_replace(self::ESCAPE_SEARCHES, self::ESCAPE_REPLACEMENTS, $item);
                }
            });
        }
        return $json;
    }
}

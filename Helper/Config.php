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

namespace DannyDamsky\DevTools\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\State;

/**
 * Class Config
 *
 * Module configuration class.
 *
 * @package DannyDamsky\DevTools\Helper
 * @since 2020-03-26
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
class Config extends AbstractHelper
{
    private const BASE_PATH = 'dev';
    private const FIELD_ID_PREFIX = 'dannydamsky_devtools_';

    private const RELATIVE_PATH_DEBUG = 'debug';
    private const RELATIVE_PATH_DEBUG_TWIG_TO_STRING = 'twig_to_string';

    private const RELATIVE_PATH_TEMPLATE = 'template';
    private const RELATIVE_PATH_TEMPLATE_TWIG_RECOMPILE = 'twig_recompile';
    private const RELATIVE_PATH_TEMPLATE_TWIG_STRICT_VARIABLES = 'twig_strict_variables';
    private const RELATIVE_PATH_TEMPLATE_TWIG_CHARSET = 'twig_charset';

    private const RELATIVE_PATH_JS = 'js';
    private const RELATIVE_PATH_JS_POLYFILL_ENABLE = 'polyfill_enable';
    private const RELATIVE_PATH_JS_POLYFILL_URL = 'polyfill_url';

    private const RELATIVE_PATH_CACHING = 'caching';
    private const RELATIVE_PATH_CACHING_TWIG_CACHE = 'twig_cache';

    private const RELATIVE_PATH_LOG_ROTATION = 'log_rotation';
    private const RELATIVE_PATH_LOG_ROTATION_ENABLED = 'enabled';
    private const RELATIVE_PATH_LOG_ROTATION_COMPRESS = 'compress';
    private const RELATIVE_PATH_LOG_ROTATION_DAYS = 'days';

    /** @var State */
    protected $_state;

    /**
     * Config constructor.
     * @param Context $context
     * @param State $state
     */
    public function __construct(Context $context, State $state)
    {
        parent::__construct($context);
        $this->_state = $state;
    }

    /**
     * Get whether the log rotation is enabled.
     *
     * @return bool
     */
    public function isLogRotationEnabled(): bool
    {
        return $this->getLogRotationConfigFlag(self::RELATIVE_PATH_LOG_ROTATION_ENABLED);
    }

    /**
     * Get whether compression for the log rotation mechanism is enabled.
     *
     * @return bool
     */
    public function isLogRotationCompressionEnabled(): bool
    {
        return $this->getLogRotationConfigFlag(self::RELATIVE_PATH_LOG_ROTATION_COMPRESS);
    }

    /**
     * Get the amount of days before logs are deleted.
     *
     * @return int
     */
    public function getLogRotationDays(): int
    {
        return (int)($this->getModuleConfigValue(self::RELATIVE_PATH_LOG_ROTATION_DAYS) ?? 14);
    }

    /**
     * Get whether polyfills are enabled.
     *
     * @return bool
     */
    public function isPolyfillEnabled(): bool
    {
        return $this->getJsConfigFlag(self::RELATIVE_PATH_JS_POLYFILL_ENABLE);
    }

    /**
     * Get the URL of the polyfill API.
     *
     * @return string
     */
    public function getPolyfillUrl(): string
    {
        return $this->getJsConfigValue(self::RELATIVE_PATH_JS_POLYFILL_URL) ??
            'https://polyfill.io/v3/polyfill.min.js?features=default%2Ces5%2Ces6%2Ces7%2CNodeList.prototype.forEach';
    }

    /**
     * Get whether the twig debug functionality is enabled.
     *
     * @return bool
     */
    public function isTwigDebugEnabled(): bool
    {
        return $this->getDebugConfigFlag(self::RELATIVE_PATH_DEBUG_TWIG_TO_STRING);
    }

    /**
     * Get whether the twig auto recompile of templates is enabled.
     *
     * @return bool
     */
    public function isTwigAutoRecompileEnabled(): bool
    {
        return $this->_state === State::MODE_DEVELOPER &&
            $this->getTemplateConfigFlag(self::RELATIVE_PATH_TEMPLATE_TWIG_RECOMPILE);
    }

    /**
     * Get whether twig strict variables are enabled.
     *
     * @return bool
     */
    public function isTwigStrictVariablesEnabled(): bool
    {
        return $this->getTemplateConfigFlag(self::RELATIVE_PATH_TEMPLATE_TWIG_STRICT_VARIABLES);
    }

    /**
     * Get whether twig template caching is enabled.
     *
     * @return bool
     */
    public function isTwigCacheEnabled(): bool
    {
        return $this->getCachingConfigFlag(self::RELATIVE_PATH_CACHING_TWIG_CACHE);
    }

    /**
     * Get the charset to be used by the twig templates.
     *
     * @return string
     */
    public function getTwigCharset(): string
    {
        return $this->getTemplateConfigValue(self::RELATIVE_PATH_TEMPLATE_TWIG_CHARSET) ?? 'UTF-8';
    }

    /**
     * Get the log rotation configuration value for the given path.
     *
     * @param string $relativePath
     * @return mixed
     */
    private function getLogRotationConfigValue(string $relativePath)
    {
        $relativePathLogRotation = self::RELATIVE_PATH_LOG_ROTATION;
        $fieldIdPrefix = self::FIELD_ID_PREFIX;
        return $this->getModuleConfigValue("{$fieldIdPrefix}{$relativePathLogRotation}/{$relativePath}");
    }

    /**
     * Get the log rotation configuration flag for the given path.
     *
     * @param string $relativePath
     * @return bool
     */
    private function getLogRotationConfigFlag(string $relativePath): bool
    {
        $relativePathLogRotation = self::RELATIVE_PATH_LOG_ROTATION;
        $fieldIdPrefix = self::FIELD_ID_PREFIX;
        return $this->getModuleConfigFlag("{$fieldIdPrefix}{$relativePathLogRotation}/{$relativePath}");
    }

    /**
     * Get the caching configuration value for the given path.
     *
     * @param string $relativePath
     * @return mixed
     */
    private function getCachingConfigValue(string $relativePath)
    {
        $relativePathCaching = self::RELATIVE_PATH_CACHING;
        $fieldIdPrefix = self::FIELD_ID_PREFIX;
        return $this->getModuleConfigValue("{$relativePathCaching}/{$fieldIdPrefix}{$relativePath}");
    }

    /**
     * Get the caching configuration flag for the given path.
     *
     * @param string $relativePath
     * @return bool
     */
    private function getCachingConfigFlag(string $relativePath): bool
    {
        $relativePathCaching = self::RELATIVE_PATH_CACHING;
        $fieldIdPrefix = self::FIELD_ID_PREFIX;
        return $this->getModuleConfigFlag("{$relativePathCaching}/{$fieldIdPrefix}{$relativePath}");
    }

    /**
     * Get the template configuration value for the given path.
     *
     * @param string $relativePath
     * @return mixed
     */
    private function getJsConfigValue(string $relativePath)
    {
        $relativePathJs = self::RELATIVE_PATH_JS;
        $fieldIdPrefix = self::FIELD_ID_PREFIX;
        return $this->getModuleConfigValue("{$relativePathJs}/{$fieldIdPrefix}{$relativePath}");
    }

    /**
     * Get the template configuration flag for the given path.
     *
     * @param string $relativePath
     * @return bool
     */
    private function getJsConfigFlag(string $relativePath): bool
    {
        $relativePathJs = self::RELATIVE_PATH_JS;
        $fieldIdPrefix = self::FIELD_ID_PREFIX;
        return $this->getModuleConfigFlag("{$relativePathJs}/{$fieldIdPrefix}{$relativePath}");
    }

    /**
     * Get the template configuration value for the given path.
     *
     * @param string $relativePath
     * @return mixed
     */
    private function getTemplateConfigValue(string $relativePath)
    {
        $relativePathTemplate = self::RELATIVE_PATH_TEMPLATE;
        $fieldIdPrefix = self::FIELD_ID_PREFIX;
        return $this->getModuleConfigValue("{$relativePathTemplate}/{$fieldIdPrefix}{$relativePath}");
    }

    /**
     * Get the template configuration flag for the given path.
     *
     * @param string $relativePath
     * @return bool
     */
    private function getTemplateConfigFlag(string $relativePath): bool
    {
        $relativePathTemplate = self::RELATIVE_PATH_TEMPLATE;
        $fieldIdPrefix = self::FIELD_ID_PREFIX;
        return $this->getModuleConfigFlag("{$relativePathTemplate}/{$fieldIdPrefix}{$relativePath}");
    }

    /**
     * Get the debug configuration value for the given path.
     *
     * @param string $relativePath
     * @return mixed
     */
    private function getDebugConfigValue(string $relativePath)
    {
        $relativePathDebug = self::RELATIVE_PATH_DEBUG;
        $fieldIdPrefix = self::FIELD_ID_PREFIX;
        return $this->getModuleConfigValue("{$relativePathDebug}/{$fieldIdPrefix}{$relativePath}");
    }

    /**
     * Get the debug configuration flag for the given path.
     *
     * @param string $relativePath
     * @return bool
     */
    private function getDebugConfigFlag(string $relativePath): bool
    {
        $relativePathDebug = self::RELATIVE_PATH_DEBUG;
        $fieldIdPrefix = self::FIELD_ID_PREFIX;
        return $this->getModuleConfigFlag("{$relativePathDebug}/{$fieldIdPrefix}{$relativePath}");
    }

    /**
     * Get the module configuration value for the given path.
     *
     * @param string $relativePath
     * @return mixed
     */
    private function getModuleConfigValue(string $relativePath)
    {
        $basePath = self::BASE_PATH;
        return $this->scopeConfig->getValue("$basePath/$relativePath");
    }

    /**
     * Get the module configuration flag for the given path.
     *
     * @param string $relativePath
     * @return bool
     */
    private function getModuleConfigFlag(string $relativePath): bool
    {
        $basePath = self::BASE_PATH;
        return $this->scopeConfig->isSetFlag("$basePath/$relativePath");
    }
}

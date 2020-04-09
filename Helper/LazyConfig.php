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

/**
 * Class LazyConfig
 *
 * Lazy-init module configuration class.
 *
 * @package DannyDamsky\DevTools\Helper
 * @since 2020-03-26
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
class LazyConfig extends Config
{
    /** @var bool|null */
    private $cachedIsTwigDebugEnabled;

    /** @var bool|null */
    private $cachedIsTwigAutoRecompileEnabled;

    /** @var bool|null */
    private $cachedIsTwigStrictVariablesEnabled;

    /** @var bool|null */
    private $cachedIsTwigCacheEnabled;

    /** @var string|null */
    private $cachedTwigCharset;

    /** @var bool|null */
    private $cachedIsPolyfillEnabled;

    /** @var string|null */
    private $cachedPolyfillUrl;

    /** @var bool|null */
    private $cachedIsLogRotationEnabled;

    /** @var bool|null */
    private $cachedIsLogRotationCompressionEnabled;

    /** @var int|null */
    private $cachedLogRotationDays;

    /**
     * @inheritDoc
     */
    public function isLogRotationEnabled(): bool
    {
        if ($this->cachedIsLogRotationEnabled === null) {
            $this->cachedIsLogRotationEnabled = parent::isLogRotationEnabled();
        }
        return $this->cachedIsLogRotationEnabled;
    }

    /**
     * @inheritDoc
     */
    public function isLogRotationCompressionEnabled(): bool
    {
        if ($this->cachedIsLogRotationCompressionEnabled === null) {
            $this->cachedIsLogRotationCompressionEnabled = parent::isLogRotationCompressionEnabled();
        }
        return $this->cachedIsLogRotationCompressionEnabled;
    }

    /**
     * @inheritDoc
     */
    public function getLogRotationDays(): int
    {
        if ($this->cachedLogRotationDays === null) {
            $this->cachedLogRotationDays = parent::getLogRotationDays();
        }
        return $this->cachedLogRotationDays;
    }

    /**
     * @inheritDoc
     */
    public function isPolyfillEnabled(): bool
    {
        if ($this->cachedIsPolyfillEnabled === null) {
            $this->cachedIsPolyfillEnabled = parent::isPolyfillEnabled();
        }
        return $this->cachedIsPolyfillEnabled;
    }

    /**
     * @inheritDoc
     */
    public function getPolyfillUrl(): string
    {
        if ($this->cachedPolyfillUrl === null) {
            $this->cachedPolyfillUrl = parent::getPolyfillUrl();
        }
        return $this->cachedPolyfillUrl;
    }

    /**
     * @inheritDoc
     */
    public function isTwigDebugEnabled(): bool
    {
        if ($this->cachedIsTwigDebugEnabled === null) {
            $this->cachedIsTwigDebugEnabled = parent::isTwigDebugEnabled();
        }
        return $this->cachedIsTwigDebugEnabled;
    }

    /**
     * @inheritDoc
     */
    public function isTwigAutoRecompileEnabled(): bool
    {
        if ($this->cachedIsTwigAutoRecompileEnabled === null) {
            $this->cachedIsTwigAutoRecompileEnabled = parent::isTwigAutoRecompileEnabled();
        }
        return $this->cachedIsTwigAutoRecompileEnabled;
    }

    /**
     * @inheritDoc
     */
    public function isTwigStrictVariablesEnabled(): bool
    {
        if ($this->cachedIsTwigStrictVariablesEnabled === null) {
            $this->cachedIsTwigStrictVariablesEnabled = parent::isTwigStrictVariablesEnabled();
        }
        return $this->cachedIsTwigStrictVariablesEnabled;
    }

    /**
     * @inheritDoc
     */
    public function isTwigCacheEnabled(): bool
    {
        if ($this->cachedIsTwigCacheEnabled === null) {
            $this->cachedIsTwigCacheEnabled = parent::isTwigCacheEnabled();
        }
        return $this->cachedIsTwigCacheEnabled;
    }

    /**
     * @inheritDoc
     */
    public function getTwigCharset(): string
    {
        if ($this->cachedTwigCharset === null) {
            $this->cachedTwigCharset = parent::getTwigCharset();
        }
        return $this->cachedTwigCharset;
    }
}

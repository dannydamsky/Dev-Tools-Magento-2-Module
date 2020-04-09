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

namespace DannyDamsky\DevTools\Api;

/**
 * Interface ClassVersionMethodInterface
 *
 * A data class used to hold a version of a specific
 * method belonging to a class.
 *
 * @package DannyDamsky\DevTools\Api
 * @since 2020-04-06
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @see ClassVersionMethodsInterface
 */
interface ClassVersionMethodInterface
{
    /**
     * Search array for the {@see str_replace()} function
     * that will be used to retrieve the method's version target.
     *
     * @var string[]
     * @see ClassVersionMethodsInterface::CLASS_METHOD_VERSION_REPLACE
     */
    public const CLASS_METHOD_VERSION_SEARCH = [ClassVersionMethodsInterface::CLASS_METHOD_PREFIX, '_'];

    /**
     * Replace array for the {@see str_replace()} function
     * that will be used to retrieve the method's version target.
     *
     * @var string[]
     * @see ClassVersionMethodsInterface::CLASS_METHOD_VERSION_SEARCH
     */
    public const CLASS_METHOD_VERSION_REPLACE = ['', '.'];

    /**
     * This method's class name.
     *
     * @var string
     */
    public const CLASSNAME = 'classname';

    /**
     * This method's name.
     *
     * @var string
     */
    public const METHOD = 'method';

    /**
     * This method's version target.
     *
     * @var string
     */
    public const VERSION = 'version';

    /**
     * Get this method's class name.
     *
     * @return string
     */
    public function getClassName(): string;

    /**
     * Set this method's class name.
     *
     * @param string $className
     * @return $this
     */
    public function setClassName(string $className): self;

    /**
     * Get this method's name.
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Set this method's name.
     *
     * @param string $method
     * @return $this
     */
    public function setMethod(string $method): self;

    /**
     * Get this method's version target.
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * Set this method's version target.
     *
     * @param string $version
     * @return $this
     */
    public function setVersion(string $version): self;
}

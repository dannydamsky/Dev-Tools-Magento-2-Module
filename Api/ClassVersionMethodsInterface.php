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
 * Interface ClassVersionMethodsInterface
 *
 * A data class used to hold version-control methods
 * that belong to a class.
 *
 * @package DannyDamsky\DevTools\Api
 * @since 2020-04-06
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
interface ClassVersionMethodsInterface
{
    /**
     * The prefix used to determine which
     * methods are considered as version-control methods.
     *
     * @var string
     */
    public const CLASS_METHOD_PREFIX = '__v';

    /**
     * The non-static methods used for version-control.
     *
     * @var string
     */
    public const METHODS = 'methods';

    /**
     * The class name that the methods belong to.
     *
     * @var string
     */
    public const CLASSNAME = 'classname';

    /**
     * Get the non-static methods used for version-control.
     *
     * @return ClassVersionMethodInterface[]
     */
    public function getMethods(): array;

    /**
     * Set the non-static methods used for version-control.
     *
     * @param ClassVersionMethodInterface[] $methods
     * @return $this
     */
    public function setMethods(array $methods): self;

    /**
     * Get the class name that the methods belong to.
     *
     * @return string
     */
    public function getClassName(): string;

    /**
     * Set the class name that the methods belong to.
     *
     * @param string $className
     * @return $this
     */
    public function setClassName(string $className): self;
}

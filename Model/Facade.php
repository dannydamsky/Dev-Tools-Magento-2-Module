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

namespace DannyDamsky\DevTools\Model;

use DannyDamsky\DevTools\Exception\BadNamespaceException;
use DannyDamsky\DevTools\Exception\ClassInitException;
use DannyDamsky\DevTools\Exception\NoSuchMethodException;
use Magento\Framework\App\ObjectManager;
use ReflectionException;
use function call_user_func_array;
use function defined;
use function explode;
use function get_class;
use function implode;
use function is_callable;

/**
 * Class Facade
 *
 * A facade class that allows calling helper class methods statically.
 *
 * @package DannyDamsky\DevTools\Model
 * @since 2020-03-24
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
abstract class Facade
{
    /**
     * The helper class map of
     * all classes that extend {@see Facade}.
     *
     * @var object
     */
    private static $helpers;

    /**
     * Get the helper class that this facade is related to.
     *
     * @return object
     * @throws ReflectionException
     * @throws BadNamespaceException
     * @noinspection PhpDocRedundantThrowsInspection
     */
    private static function getHelper(): object
    {
        $namespace = static::class;
        if (isset(self::$helpers[$namespace])) {
            return self::$helpers[$namespace];
        }
        $objectManager = ObjectManager::getInstance();
        if (defined("$namespace::FACADE_HELPER")) {
            /** @noinspection PhpUndefinedFieldInspection */
            return self::$helpers[$namespace] = $objectManager->get($namespace::FACADE_HELPER);
        }
        $namespaceParts = explode('\\', $namespace, 4);
        if (!isset($namespaceParts[3])) {
            throw BadNamespaceException::badFacadeNamespace();
        }
        $facade = &$namespaceParts[2];
        if ($facade !== 'Facade') {
            throw BadNamespaceException::badFacadeNamespace();
        }
        $facade = 'Helper';
        $helperNamespace = implode('\\', $namespaceParts);
        return self::$helpers[$namespace] = $objectManager->get($helperNamespace);
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     * @throws BadNamespaceException
     */
    final public static function __callStatic(string $name, array $arguments)
    {
        $helper = self::getHelper();
        $callableArray = [$helper, $name];
        if (!is_callable($callableArray)) {
            throw NoSuchMethodException::build(get_class($helper), $name);
        }
        return call_user_func_array($callableArray, $arguments);
    }

    /**
     * Facade constructor.
     */
    final public function __construct()
    {
        throw ClassInitException::constructorIsDisabled();
    }
}

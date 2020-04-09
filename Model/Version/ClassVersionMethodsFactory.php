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

namespace DannyDamsky\DevTools\Model\Version;

use DannyDamsky\DevTools\Api\ClassVersionMethodFactoryInterface;
use DannyDamsky\DevTools\Api\ClassVersionMethodInterface;
use DannyDamsky\DevTools\Api\ClassVersionMethodsFactoryInterface;
use DannyDamsky\DevTools\Api\ClassVersionMethodsInterface;
use DannyDamsky\DevTools\Model\AbstractModelFactory;
use Magento\Framework\ObjectManagerInterface;
use ReflectionClass;
use function str_contains;
use function usort;
use function version_compare;

/**
 * Class ClassVersionMethodsFactory
 * @package DannyDamsky\DevTools\Model\Version
 * @since 2020-04-06
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
class ClassVersionMethodsFactory extends AbstractModelFactory implements ClassVersionMethodsFactoryInterface
{
    /**
     * Class version method factory instance.
     *
     * @var ClassVersionMethodFactoryInterface
     */
    protected $_classVersionMethodFactory;

    /**
     * ClassVersionMethodsFactory constructor.
     * @param ObjectManagerInterface $objectManager
     * @param ClassVersionMethodFactoryInterface $classVersionMethodFactory
     * @param string $instanceName
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        ClassVersionMethodFactoryInterface $classVersionMethodFactory,
        string $instanceName = ClassVersionMethodsInterface::class
    )
    {
        parent::__construct($objectManager, $instanceName);
        $this->_classVersionMethodFactory = $classVersionMethodFactory;
    }

    /**
     * @inheritDoc
     */
    public function create(string $className): ClassVersionMethodsInterface
    {
        $reflectionClass = new ReflectionClass($className);
        $methods = [];
        foreach ($reflectionClass->getMethods() as $method) {
            $methodName = $method->getName();
            if (str_contains($methodName, ClassVersionMethodsInterface::CLASS_METHOD_PREFIX)) {
                $methods[] = $this->_classVersionMethodFactory->create($className, $methodName);
            }
        }
        usort($methods, static function (ClassVersionMethodInterface $arg1, ClassVersionMethodInterface $arg2): bool {
            return version_compare($arg1->getVersion(), $arg2->getVersion(), '>');
        });
        /** @var ClassVersionMethodsInterface $instance */
        $instance = $this->__create(
            [
                ClassVersionMethodsInterface::METHODS => $methods,
                ClassVersionMethodsInterface::CLASSNAME => $className
            ]
        );
        return $instance;
    }
}

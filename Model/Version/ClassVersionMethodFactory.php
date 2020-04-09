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
use DannyDamsky\DevTools\Model\AbstractModelFactory;
use Magento\Framework\ObjectManagerInterface;
use function str_replace;

/**
 * Class ClassVersionMethodFactory
 * @package DannyDamsky\DevTools\Model\Version
 * @since 2020-04-06
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
class ClassVersionMethodFactory extends AbstractModelFactory implements ClassVersionMethodFactoryInterface
{
    /**
     * ClassVersionMethodFactory constructor.
     * @param ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        string $instanceName = ClassVersionMethodInterface::class
    )
    {
        parent::__construct($objectManager, $instanceName);
    }

    /**
     * @inheritDoc
     */
    public function create(string $className, string $method): ClassVersionMethodInterface
    {
        /** @var ClassVersionMethodInterface $instance */
        $instance = $this->__create(
            [
                ClassVersionMethodInterface::CLASSNAME => $className,
                ClassVersionMethodInterface::METHOD => $method,
                ClassVersionMethodInterface::VERSION => str_replace(
                    ClassVersionMethodInterface::CLASS_METHOD_VERSION_SEARCH,
                    ClassVersionMethodInterface::CLASS_METHOD_VERSION_REPLACE,
                    $method
                )
            ]
        );
        return $instance;
    }
}

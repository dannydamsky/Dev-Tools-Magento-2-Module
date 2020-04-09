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

use DannyDamsky\DevTools\Api\ClassVersionMethodInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class ClassVersionMethod
 * @package DannyDamsky\DevTools\Model\Version
 * @since 2020-04-06
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
class ClassVersionMethod extends AbstractModel implements ClassVersionMethodInterface
{
    /**
     * @inheritDoc
     */
    public function getClassName(): string
    {
        return $this->getData(self::CLASSNAME);
    }

    /**
     * @inheritDoc
     */
    public function setClassName(string $className): ClassVersionMethodInterface
    {
        return $this->setData(self::CLASSNAME, $className);
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->getData(self::METHOD);
    }

    /**
     * @inheritDoc
     */
    public function setMethod(string $method): ClassVersionMethodInterface
    {
        return $this->setData(self::METHOD, $method);
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return $this->getData(self::VERSION);
    }

    /**
     * @inheritDoc
     */
    public function setVersion(string $version): ClassVersionMethodInterface
    {
        return $this->setData(self::VERSION, $version);
    }
}

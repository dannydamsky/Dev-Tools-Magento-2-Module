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

namespace DannyDamsky\DevTools\Model\Setup\Migration;

use DannyDamsky\DevTools\Api\ColumnDefinitionInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class ColumnDefinition
 * @package DannyDamsky\DevTools\Model\Setup\Migration
 * @since 2020-03-26
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
class ColumnDefinition extends AbstractModel implements ColumnDefinitionInterface
{
    /**
     * @inheritDoc
     */
    public function setColumnName(string $columnName): ColumnDefinitionInterface
    {
        return $this->setData(self::COLUMN_NAME, $columnName);
    }

    /**
     * @inheritDoc
     */
    public function getColumnName(): ?string
    {
        return $this->getData(self::COLUMN_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setDefault($default): ColumnDefinitionInterface
    {
        return $this->setData(self::DEFAULT, $default);
    }

    /**
     * @inheritDoc
     */
    public function getDefault()
    {
        return $this->getData(self::DEFAULT);
    }

    /**
     * @inheritDoc
     */
    public function setIdentity($identity): ColumnDefinitionInterface
    {
        return $this->setData(self::IDENTITY, $identity);
    }

    /**
     * @inheritDoc
     */
    public function getIdentity()
    {
        return $this->getData(self::IDENTITY);
    }

    /**
     * @inheritDoc
     */
    public function setLength(?int $length): ColumnDefinitionInterface
    {
        return $this->setData(self::LENGTH, $length);
    }

    /**
     * @inheritDoc
     */
    public function getLength(): ?int
    {
        return $this->getData(self::LENGTH);
    }

    /**
     * @inheritDoc
     */
    public function setNullable(?bool $nullable): ColumnDefinitionInterface
    {
        return $this->setData(self::NULLABLE, $nullable);
    }

    /**
     * @inheritDoc
     */
    public function getNullable(): ?bool
    {
        return $this->getData(self::NULLABLE);
    }

    /**
     * @inheritDoc
     */
    public function setPrecision($precision): ColumnDefinitionInterface
    {
        return $this->setData(self::PRECISION, $precision);
    }

    /**
     * @inheritDoc
     */
    public function getPrecision()
    {
        return $this->getData(self::PRECISION);
    }

    /**
     * @inheritDoc
     */
    public function setPrimary(?bool $primary): ColumnDefinitionInterface
    {
        return $this->setData(self::PRIMARY, $primary);
    }

    /**
     * @inheritDoc
     */
    public function getPrimary(): ?bool
    {
        return $this->getData(self::PRIMARY);
    }

    /**
     * @inheritDoc
     */
    public function setScale($scale): ColumnDefinitionInterface
    {
        return $this->setData(self::SCALE, $scale);
    }

    /**
     * @inheritDoc
     */
    public function getScale()
    {
        return $this->getData(self::SCALE);
    }

    /**
     * @inheritDoc
     */
    public function setType(string $type): ColumnDefinitionInterface
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * @inheritDoc
     */
    public function getType(): ?string
    {
        return $this->getData(self::TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setUnsigned(?bool $unsigned): ColumnDefinitionInterface
    {
        return $this->setData(self::UNSIGNED, $unsigned);
    }

    /**
     * @inheritDoc
     */
    public function getUnsigned(): ?bool
    {
        return $this->getData(self::UNSIGNED);
    }

    /**
     * @inheritDoc
     */
    public function setComment(?string $comment): ColumnDefinitionInterface
    {
        return $this->setData(self::COMMENT, $comment);
    }

    /**
     * @inheritDoc
     */
    public function getComment(): ?string
    {
        return $this->getData(self::COMMENT);
    }

    /**
     * @inheritDoc
     */
    public function toArray(array $keys = []): array
    {
        if ($keys === ['-']) {
            $array = parent::toArray();
            unset($array[self::COLUMN_NAME]);
            return $array;
        }
        $array = parent::toArray($keys);
        unset($array[self::COLUMN_NAME], $array[self::LENGTH], $array[self::COMMENT], $array[self::TYPE]);
        return $array;
    }
}

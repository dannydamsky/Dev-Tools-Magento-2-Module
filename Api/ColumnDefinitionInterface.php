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

use Magento\Framework\DB\Ddl\Table;

/**
 * Interface ColumnDefinitionInterface
 *
 * A data class used to hold the definition of a database column.
 *
 * @package DannyDamsky\DevTools\Api
 * @since 2020-03-27
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
interface ColumnDefinitionInterface
{
    /**
     * Column name.
     *
     * @var string
     */
    public const COLUMN_NAME = 'column_name';

    /**
     * Default column value.
     *
     * @var string
     */
    public const DEFAULT = Table::OPTION_DEFAULT;

    /**
     * Column identity setting.
     *
     * @var string
     */
    public const IDENTITY = Table::OPTION_IDENTITY;

    /**
     * Column length.
     *
     * @var string
     */
    public const LENGTH = Table::OPTION_LENGTH;

    /**
     * Controls whether the column can be nullable or not.
     *
     * @var string
     */
    public const NULLABLE = Table::OPTION_NULLABLE;

    /**
     * Column precision setting.
     *
     * @var string
     */
    public const PRECISION = Table::OPTION_PRECISION;

    /**
     * Column primary key part setting.
     *
     * @var string
     */
    public const PRIMARY = Table::OPTION_PRIMARY;

    /**
     * Column scale setting.
     *
     * @var string
     */
    public const SCALE = Table::OPTION_SCALE;

    /**
     * Column type setting.
     *
     * @var string
     */
    public const TYPE = Table::OPTION_TYPE;

    /**
     * Controls whether the integer type column is unsigned.
     *
     * @var string
     */
    public const UNSIGNED = Table::OPTION_UNSIGNED;

    /**
     * Column comment.
     *
     * @var string
     */
    public const COMMENT = 'comment';

    /**
     * Set the column name.
     *
     * @param string $columnName
     * @return $this
     */
    public function setColumnName(string $columnName): self;

    /**
     * Get the column name.
     *
     * @return string|null
     */
    public function getColumnName(): ?string;

    /**
     * Set the default column value.
     *
     * @param mixed $default
     * @return $this
     */
    public function setDefault($default): self;

    /**
     * Get the default column value.
     *
     * @return mixed
     */
    public function getDefault();

    /**
     * Set the column identity.
     *
     * @param int|bool|null $identity True if column is auto-generated with unique values.
     * @return $this
     */
    public function setIdentity($identity): self;

    /**
     * Get the column identity.
     *
     * @return int|bool|null True if column is auto-generated with unique values.
     */
    public function getIdentity();

    /**
     * Set the length of the column.
     *
     * @param int|null $length
     * @return $this
     */
    public function setLength(?int $length): self;

    /**
     * Get the length of the column.
     *
     * @return int|null
     */
    public function getLength(): ?int;

    /**
     * Set whether the column is nullable.
     *
     * @param bool|null $nullable
     * @return $this
     */
    public function setNullable(?bool $nullable): self;

    /**
     * Get whether the column is nullable.
     *
     * @return bool|null
     */
    public function getNullable(): ?bool;

    /**
     * Set the column precision.
     *
     * @param int|float|null $precision
     * @return $this
     */
    public function setPrecision($precision): self;

    /**
     * Get the column precision.
     *
     * @return int|float|null
     */
    public function getPrecision();

    /**
     * Set whether the column is part of the primary key.
     *
     * @param bool|null $primary
     * @return $this
     */
    public function setPrimary(?bool $primary): self;

    /**
     * Get whether the column is part of the primary key.
     *
     * @return bool|null
     */
    public function getPrimary(): ?bool;

    /**
     * Set the column scale.
     *
     * @param int|float|null $scale
     * @return $this
     */
    public function setScale($scale): self;

    /**
     * Get the column scale.
     *
     * @return int|float|null
     */
    public function getScale();

    /**
     * Set the column type.
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self;

    /**
     * Get the column type.
     *
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * Set whether the column is an unsigned integer type.
     *
     * @param bool|null $unsigned
     * @return $this
     */
    public function setUnsigned(?bool $unsigned): self;

    /**
     * Get whether the column is an unsigned integer type.
     *
     * @return bool|null
     */
    public function getUnsigned(): ?bool;

    /**
     * Set the column comment.
     *
     * @param string $comment
     * @return $this
     */
    public function setComment(?string $comment): self;

    /**
     * Get the column comment.
     *
     * @return string
     */
    public function getComment(): ?string;
}

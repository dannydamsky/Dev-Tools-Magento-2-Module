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
use Magento\Framework\Model\AbstractModel;

/**
 * Interface BlueprintInterface
 *
 * A data class that is used to hold a table's schema blueprint.
 *
 * @package DannyDamsky\DevTools\Api
 * @since 2020-03-27
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
interface BlueprintInterface
{
    /**
     * Create a boolean column.
     *
     * @param string $columnName
     * @return ColumnDefinitionInterface|AbstractModel
     */
    public function boolean(string $columnName): ColumnDefinitionInterface;

    /**
     * Create a small integer column.
     *
     * @param string $columnName
     * @param bool $unsigned
     * @return ColumnDefinitionInterface|AbstractModel
     */
    public function smallint(string $columnName, bool $unsigned = false): ColumnDefinitionInterface;

    /**
     * Create an integer column.
     *
     * @param string $columnName
     * @param bool $unsigned
     * @return ColumnDefinitionInterface|AbstractModel
     */
    public function integer(string $columnName, bool $unsigned = false): ColumnDefinitionInterface;

    /**
     * Create a big integer column.
     *
     * @param string $columnName
     * @param bool $unsigned
     * @return ColumnDefinitionInterface|AbstractModel
     */
    public function bigint(string $columnName, bool $unsigned = false): ColumnDefinitionInterface;

    /**
     * Create a float column.
     *
     * @param string $columnName
     * @param bool $unsigned
     * @return ColumnDefinitionInterface|AbstractModel
     */
    public function float(string $columnName, bool $unsigned = false): ColumnDefinitionInterface;

    /**
     * Create a numeric column.
     *
     * @param string $columnName
     * @param bool $unsigned
     * @param int|null $size
     * @return ColumnDefinitionInterface|AbstractModel
     */
    public function numeric(string $columnName, bool $unsigned = false, ?int $size = null): ColumnDefinitionInterface;

    /**
     * Create a date column.
     *
     * @param string $columnName
     * @return ColumnDefinitionInterface|AbstractModel
     */
    public function date(string $columnName): ColumnDefinitionInterface;

    /**
     * Create a timestamp column.
     *
     * @param string $columnName
     * @return ColumnDefinitionInterface|AbstractModel
     */
    public function timestamp(string $columnName): ColumnDefinitionInterface;

    /**
     * Create a creation timestamp column.
     *
     * @param string $columnName
     * @return ColumnDefinitionInterface|AbstractModel
     */
    public function creationTimestamp(string $columnName = 'created_at'): ColumnDefinitionInterface;

    /**
     * Create a modification timestamp column.
     *
     * @param string $columnName
     * @return ColumnDefinitionInterface|AbstractModel
     */
    public function modificationTimestamp(string $columnName = 'updated_at'): ColumnDefinitionInterface;

    /**
     * Create creation and modification timestamp columns.
     *
     * @param string $createdAtColumnName
     * @param string $updatedAtColumnName
     */
    public function timestamps(string $createdAtColumnName = 'created_at', string $updatedAtColumnName = 'updated_at'): void;

    /**
     * Create a date time column.
     *
     * @param string $columnName
     * @return ColumnDefinitionInterface|AbstractModel
     */
    public function dateTime(string $columnName): ColumnDefinitionInterface;

    /**
     * Create a text column.
     *
     * @param string $columnName
     * @param int|null $size
     * @return ColumnDefinitionInterface|AbstractModel
     */
    public function text(string $columnName, ?int $size = null): ColumnDefinitionInterface;

    /**
     * Create a blob column.
     *
     * @param string $columnName
     * @return ColumnDefinitionInterface|AbstractModel
     */
    public function blob(string $columnName): ColumnDefinitionInterface;

    /**
     * Create a varbinary column.
     *
     * @param string $columnName
     * @return ColumnDefinitionInterface|AbstractModel
     */
    public function varbinary(string $columnName): ColumnDefinitionInterface;

    /**
     * Add Foreign Key to table
     *
     * @param string $fkName the foreign key name
     * @param string $column the foreign key column name
     * @param string $refTable the reference table name
     * @param string $refColumn the reference table column name
     * @param string $onDelete the action on delete row
     */
    public function addForeignKey(string $fkName, string $column, string $refTable, string $refColumn, ?string $onDelete = null): void;

    /**
     * Add index to table.
     *
     * @param string $indexName
     * @param string|array $fields
     * @param array $options
     */
    public function addIndex(string $indexName, $fields, array $options = []): void;

    /**
     * Get the list of all column definitions that were created.
     *
     * @return ColumnDefinitionInterface[]|AbstractModel[]
     */
    public function getColumnDefinitions(): array;

    /**
     * Get the list of all foreign key definitions that were created.
     *
     * @return array
     */
    public function getForeignKeyDefinitions(): array;

    /**
     * Get the list of all index definitions that were created.
     *
     * @return array
     */
    public function getIndexDefinitions(): array;
}

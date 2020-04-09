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

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Throwable;

/**
 * Interface SchemaInterface
 *
 * A data class used to process a database schema.
 *
 * @package DannyDamsky\DevTools\Api
 * @since 2020-03-27
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
interface SchemaInterface
{
    /**
     * Initialize the properties from the schema class.
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @param AdapterInterface|null $connection
     */
    public function initializeSchemaProperties(SchemaSetupInterface $setup, ModuleContextInterface $context, ?AdapterInterface $connection = null): void;

    /**
     * Create/Alter an existing table for the given name.
     *
     * @param string $tableName
     * @param callable $modifier
     * @throws Throwable
     */
    public function table(string $tableName, callable $modifier): void;

    /**
     * Create a new table for the given table name.
     *
     * @param string $tableName
     * @param callable $modifier
     * @throws Throwable
     */
    public function newTable(string $tableName, callable $modifier): void;

    /**
     * Alter an existing table for the given table name.
     *
     * @param string $tableName
     * @param callable $modifier
     * @throws Throwable
     */
    public function alterTable(string $tableName, callable $modifier): void;

    /**
     * Add an index to the given column name for the given table name.
     *
     * @param string $tableName
     * @param string|array $columns
     * @param string $indexType
     */
    public function addIndex(string $tableName, $columns, string $indexType = AdapterInterface::INDEX_TYPE_INDEX): void;

    /**
     * Drop an index from the given column name for the given table name.
     *
     * @param string $tableName
     * @param string|array $columns
     * @param string $indexType
     */
    public function dropIndex(string $tableName, $columns, string $indexType = AdapterInterface::INDEX_TYPE_INDEX): void;

    /**
     * Drop the given column name from the given table name.
     *
     * @param string $tableName
     * @param string $columnName
     */
    public function dropColumn(string $tableName, string $columnName): void;

    /**
     * Drop the given foreign key name from the given table name.
     *
     * @param string $tableName
     * @param string $foreignKeyName
     */
    public function dropForeignKey(string $tableName, string $foreignKeyName): void;
}

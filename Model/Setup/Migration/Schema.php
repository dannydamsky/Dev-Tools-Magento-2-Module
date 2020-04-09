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

use DannyDamsky\DevTools\Api\BlueprintFactoryInterface;
use DannyDamsky\DevTools\Api\SchemaInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use function var_dump;

/**
 * Class Schema
 * @package DannyDamsky\DevTools\Model\Setup\Migration
 * @since 2020-03-26
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
class Schema implements SchemaInterface
{
    /** @var SchemaSetupInterface */
    protected $_setup;

    /** @var ModuleContextInterface */
    protected $_context;

    /** @var AdapterInterface */
    protected $_connection;

    /** @var BlueprintFactoryInterface */
    protected $_blueprintFactory;

    /**
     * Schema constructor.
     * @param BlueprintFactoryInterface $blueprintFactory
     */
    public function __construct(BlueprintFactoryInterface $blueprintFactory)
    {
        $this->_blueprintFactory = $blueprintFactory;
    }

    /**
     * @inheritDoc
     */
    public function initializeSchemaProperties(SchemaSetupInterface $setup, ModuleContextInterface $context, ?AdapterInterface $connection = null): void
    {
        $this->_setup = $setup;
        $this->_context = $context;
        $this->_connection = $connection ?? $setup->getConnection();
    }

    /**
     * @inheritDoc
     */
    public function table(string $tableName, callable $modifier): void
    {
        if ($this->_setup->tableExists($this->_setup->getTable($tableName))) {
            $this->alterTable($tableName, $modifier);
        } else {
            $this->newTable($tableName, $modifier);
        }
    }

    /**
     * @inheritDoc
     */
    public function newTable(string $tableName, callable $modifier): void
    {
        $tableName = $this->_setup->getTable($tableName);
        $table = $this->_connection->newTable($tableName);
        $blueprint = $this->_blueprintFactory->create();
        $modifier($blueprint);
        foreach ($blueprint->getColumnDefinitions() as $columnDefinition) {
            $table->addColumn(
                $columnDefinition->getColumnName(),
                $columnDefinition->getType(),
                $columnDefinition->getLength(),
                $columnDefinition->toArray(),
                $columnDefinition->getComment()
            );
        }
        foreach ($blueprint->getForeignKeyDefinitions() as $foreignKeyDefinition) {
            $table->addForeignKey(
                $foreignKeyDefinition[0], // $fkName
                $foreignKeyDefinition[1], // $column
                $foreignKeyDefinition[2], // $refTable
                $foreignKeyDefinition[3], // $refColumn
                $foreignKeyDefinition[4]  // $onDelete
            );
        }
        foreach ($blueprint->getIndexDefinitions() as $indexDefinition) {
            $table->addIndex(
                $indexDefinition[0], // $indexName
                $indexDefinition[1], // $fields
                $indexDefinition[2]  // $options
            );
        }
        $this->_connection->createTable($table);
    }

    /**
     * @inheritDoc
     */
    public function alterTable(string $tableName, callable $modifier): void
    {
        $tableName = $this->_setup->getTable($tableName);
        $blueprint = $this->_blueprintFactory->create();
        $modifier($blueprint);
        foreach ($blueprint->getColumnDefinitions() as $columnDefinition) {
            $columnName = $columnDefinition->getColumnName();
            $columnDefinitionArray = $columnDefinition->toArray(['-']);
            if ($this->_connection->tableColumnExists($tableName, $columnName)) {
                $this->_connection->changeColumn($tableName, $columnName, $columnName, $columnDefinitionArray);
            } else {
                $this->_connection->addColumn($tableName, $columnName, $columnDefinitionArray);
            }
        }
        foreach ($blueprint->getForeignKeyDefinitions() as $foreignKeyDefinition) {
            $this->_connection->addForeignKey(
                $foreignKeyDefinition[0], // $fkName
                $tableName,
                $foreignKeyDefinition[1], // $column
                $foreignKeyDefinition[2], // $refTable,
                $foreignKeyDefinition[3], // $refColumn
                $foreignKeyDefinition[4]  // $onDelete
            );
        }
        foreach ($blueprint->getIndexDefinitions() as $indexDefinition) {
            $this->_connection->addIndex(
                $tableName,
                $indexDefinition[0], // $indexName
                $indexDefinition[1], // $fields
                $indexDefinition[2]  // $options
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function addIndex(string $tableName, $columns, string $indexType = AdapterInterface::INDEX_TYPE_INDEX): void
    {
        $tableName = $this->_setup->getTable($tableName);
        $indexName = $this->_setup->getIdxName($tableName, $columns, $indexType);
        $this->_connection->addIndex($tableName, $indexName, $columns, $indexType);
    }

    /**
     * @inheritDoc
     */
    public function dropIndex(string $tableName, $columns, string $indexType = AdapterInterface::INDEX_TYPE_INDEX): void
    {
        $tableName = $this->_setup->getTable($tableName);
        $indexName = $this->_setup->getIdxName($tableName, $columns, $indexType);
        $this->_connection->dropIndex($tableName, $indexName);
    }

    /**
     * @inheritDoc
     */
    public function dropColumn(string $tableName, string $columnName): void
    {
        $tableName = $this->_setup->getTable($tableName);
        $this->_connection->dropColumn($tableName, $columnName);
    }

    /**
     * @inheritDoc
     */
    public function dropForeignKey(string $tableName, string $foreignKeyName): void
    {
        $tableName = $this->_setup->getTable($tableName);
        $this->_connection->dropForeignKey($tableName, $foreignKeyName);
    }
}

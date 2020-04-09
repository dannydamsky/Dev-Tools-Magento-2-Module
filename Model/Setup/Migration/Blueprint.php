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

use DannyDamsky\DevTools\Api\BlueprintInterface;
use DannyDamsky\DevTools\Api\ColumnDefinitionFactoryInterface;
use DannyDamsky\DevTools\Api\ColumnDefinitionInterface;
use Magento\Framework\DB\Ddl\Table;
use function __;

/**
 * Class Blueprint
 * @package DannyDamsky\DevTools\Model\Setup\Migration
 * @since 2020-03-26
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
class Blueprint implements BlueprintInterface
{
    /**
     * Column definition factory instance.
     *
     * @var ColumnDefinitionFactoryInterface
     */
    protected $columnDefinitionFactory;

    /**
     * A list of column definitions.
     *
     * @var ColumnDefinitionInterface[]
     */
    protected $columnDefinitions = [];

    /**
     * A list of index definitions.
     *
     * @var array
     */
    protected $indexDefinitions = [];

    /**
     * A list of foreign key definitions.
     *
     * @var array
     */
    protected $foreignKeyDefinitions = [];

    /**
     * Blueprint constructor.
     * @param ColumnDefinitionFactoryInterface $columnDefinitionFactory
     */
    public function __construct(ColumnDefinitionFactoryInterface $columnDefinitionFactory)
    {
        $this->columnDefinitionFactory = $columnDefinitionFactory;
    }

    /**
     * @inheritDoc
     */
    public function boolean(string $columnName): ColumnDefinitionInterface
    {
        $definition = $this->columnDefinitionFactory->create();
        $definition->setColumnName($columnName);
        $definition->setType(Table::TYPE_BOOLEAN);
        $this->columnDefinitions[] = $definition;
        return $definition;
    }

    /**
     * @inheritDoc
     */
    public function smallint(string $columnName, bool $unsigned = false): ColumnDefinitionInterface
    {
        $definition = $this->columnDefinitionFactory->create();
        $definition->setColumnName($columnName);
        $definition->setType(Table::TYPE_SMALLINT);
        $definition->setUnsigned($unsigned);
        $this->columnDefinitions[] = $definition;
        return $definition;
    }

    /**
     * @inheritDoc
     */
    public function integer(string $columnName, bool $unsigned = false): ColumnDefinitionInterface
    {
        $definition = $this->columnDefinitionFactory->create();
        $definition->setColumnName($columnName);
        $definition->setType(Table::TYPE_INTEGER);
        $definition->setUnsigned($unsigned);
        $this->columnDefinitions[] = $definition;
        return $definition;
    }

    /**
     * @inheritDoc
     */
    public function bigint(string $columnName, bool $unsigned = false): ColumnDefinitionInterface
    {
        $definition = $this->columnDefinitionFactory->create();
        $definition->setColumnName($columnName);
        $definition->setType(Table::TYPE_BIGINT);
        $definition->setUnsigned($unsigned);
        $this->columnDefinitions[] = $definition;
        return $definition;
    }

    /**
     * @inheritDoc
     */
    public function float(string $columnName, bool $unsigned = false): ColumnDefinitionInterface
    {
        $definition = $this->columnDefinitionFactory->create();
        $definition->setColumnName($columnName);
        $definition->setType(Table::TYPE_FLOAT);
        $definition->setUnsigned($unsigned);
        $this->columnDefinitions[] = $definition;
        return $definition;
    }

    /**
     * @inheritDoc
     */
    public function numeric(string $columnName, bool $unsigned = false, ?int $size = null): ColumnDefinitionInterface
    {
        $definition = $this->columnDefinitionFactory->create();
        $definition->setColumnName($columnName);
        $definition->setType(Table::TYPE_NUMERIC);
        $definition->setUnsigned($unsigned);
        $this->columnDefinitions[] = $definition;
        return $definition;
    }

    /**
     * @inheritDoc
     */
    public function date(string $columnName): ColumnDefinitionInterface
    {
        $definition = $this->columnDefinitionFactory->create();
        $definition->setColumnName($columnName);
        $definition->setType(Table::TYPE_DATE);
        $this->columnDefinitions[] = $definition;
        return $definition;
    }

    /**
     * @inheritDoc
     */
    public function timestamp(string $columnName): ColumnDefinitionInterface
    {
        $definition = $this->columnDefinitionFactory->create();
        $definition->setColumnName($columnName);
        $definition->setType(Table::TYPE_TIMESTAMP);
        $this->columnDefinitions[] = $definition;
        return $definition;
    }

    /**
     * @inheritDoc
     */
    public function creationTimestamp(string $columnName = 'created_at'): ColumnDefinitionInterface
    {
        $definition = $this->timestamp($columnName);
        $definition->setNullable(false);
        $definition->setDefault(Table::TIMESTAMP_INIT);
        $definition->setComment(__('Creation Timestamp'));
        return $definition;
    }

    /**
     * @inheritDoc
     */
    public function modificationTimestamp(string $columnName = 'updated_at'): ColumnDefinitionInterface
    {
        $definition = $this->timestamp($columnName);
        $definition->setNullable(false);
        $definition->setDefault(Table::TIMESTAMP_INIT_UPDATE);
        $definition->setComment(__('Modification Timestamp'));
        return $definition;
    }

    /**
     * @inheritDoc
     */
    public function timestamps(string $createdAtColumnName = 'created_at', string $updatedAtColumnName = 'updated_at'): void
    {
        $this->creationTimestamp($createdAtColumnName);
        $this->modificationTimestamp($updatedAtColumnName);
    }

    /**
     * @inheritDoc
     */
    public function dateTime(string $columnName): ColumnDefinitionInterface
    {
        $definition = $this->columnDefinitionFactory->create();
        $definition->setColumnName($columnName);
        $definition->setType(Table::TYPE_DATETIME);
        $this->columnDefinitions[] = $definition;
        return $definition;
    }

    /**
     * @inheritDoc
     */
    public function text(string $columnName, ?int $size = null): ColumnDefinitionInterface
    {
        $definition = $this->columnDefinitionFactory->create();
        $definition->setColumnName($columnName);
        $definition->setType(Table::TYPE_TEXT);
        $definition->setLength($size);
        $this->columnDefinitions[] = $definition;
        return $definition;
    }

    /**
     * @inheritDoc
     */
    public function blob(string $columnName): ColumnDefinitionInterface
    {
        $definition = $this->columnDefinitionFactory->create();
        $definition->setColumnName($columnName);
        $definition->setType(Table::TYPE_BLOB);
        $this->columnDefinitions[] = $definition;
        return $definition;
    }

    /**
     * @inheritDoc
     */
    public function varbinary(string $columnName): ColumnDefinitionInterface
    {
        $definition = $this->columnDefinitionFactory->create();
        $definition->setColumnName($columnName);
        $definition->setType(Table::TYPE_VARBINARY);
        $this->columnDefinitions[] = $definition;
        return $definition;
    }

    /**
     * @inheritDoc
     */
    public function addForeignKey(string $fkName, string $column, string $refTable, string $refColumn, ?string $onDelete = null): void
    {
        $this->foreignKeyDefinitions[] = [$fkName, $column, $refTable, $refColumn, $onDelete];
    }

    /**
     * @inheritDoc
     */
    public function addIndex(string $indexName, $fields, array $options = []): void
    {
        $this->indexDefinitions[] = [$indexName, $fields, $options];
    }

    /**
     * @inheritDoc
     */
    public function getColumnDefinitions(): array
    {
        return $this->columnDefinitions;
    }

    /**
     * @inheritDoc
     */
    public function getForeignKeyDefinitions(): array
    {
        return $this->foreignKeyDefinitions;
    }

    /**
     * @inheritDoc
     */
    public function getIndexDefinitions(): array
    {
        return $this->indexDefinitions;
    }
}

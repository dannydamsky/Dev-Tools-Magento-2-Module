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

namespace DannyDamsky\DevTools\Model\Setup;

use DannyDamsky\DevTools\Api\DatabaseMigrationInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;
use Throwable;

/**
 * Class Uninstall
 *
 * A migration class meant to simplify working with {@see UninstallInterface}.
 *
 * @package DannyDamsky\DevTools\Model\Setup
 * @since 2020-03-26
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
abstract class Uninstall implements UninstallInterface, DatabaseMigrationInterface
{
    /** @var SchemaSetupInterface */
    protected $_setup;

    /** @var ModuleContextInterface */
    protected $_context;

    /** @var AdapterInterface */
    protected $_connection;

    /**
     * @inheritDoc
     * @throws Throwable
     */
    final public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $this->_setup = $setup;
        $this->_context = $context;
        $this->_connection = $setup->getConnection();
        $setup->startSetup();
        try {
            $this->execute();
        } finally {
            $setup->endSetup();
        }
    }

    /**
     * Drop the given table name.
     *
     * @param string $tableName
     */
    protected function dropTable(string $tableName): void
    {
        $tableName = $this->_setup->getTable($tableName);
        if ($this->_setup->tableExists($tableName)) {
            $this->_connection->dropTable($tableName);
        }
    }
}

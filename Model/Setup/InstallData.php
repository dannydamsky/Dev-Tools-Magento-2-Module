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

use DannyDamsky\DevTools\Api\ClassVersionMethodsFactoryInterface;
use DannyDamsky\DevTools\Api\ClassVersionMethodsInterface;
use DannyDamsky\DevTools\Api\DatabaseMigrationInterface;
use DannyDamsky\DevTools\Traits\MigrationTrait;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use ReflectionException;
use Throwable;
use function version_compare;

/**
 * Class InstallData
 *
 * A migration class meant to simplify working with {@see InstallDataInterface}.
 *
 * @package DannyDamsky\DevTools\Model\Setup
 * @since 2020-03-26
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
abstract class InstallData implements InstallDataInterface, DatabaseMigrationInterface
{
    /** @var ModuleDataSetupInterface */
    protected $_setup;

    /** @var ModuleContextInterface */
    protected $_context;

    /** @var ClassVersionMethodsInterface */
    private $classVersionMethods;

    /**
     * InstallData constructor.
     *
     * Magento's setup classes do not support
     * constructor dependency injection for custom
     * classes.
     *
     * That's why the function takes object manager in the constructor to create the required
     * the class' dependencies.
     *
     * @param ObjectManagerInterface $objectManager
     * @throws ReflectionException
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        /** @var ClassVersionMethodsFactoryInterface $classVersionMethodsFactory */
        $classVersionMethodsFactory = $objectManager->get(ClassVersionMethodsFactoryInterface::class);
        $this->classVersionMethods = $classVersionMethodsFactory->create(static::class);
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    final public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context): void
    {
        $this->_setup = $setup;
        $this->_context = $context;
        $setup->startSetup();
        try {
            $this->execute();
            $this->executeVersionSpecificFunctions();
        } finally {
            $setup->endSetup();
        }
    }

    /**
     * Execute functions that are specific for a version of the module.
     */
    private function executeVersionSpecificFunctions(): void
    {
        $moduleVersion = $this->_context->getVersion();
        foreach ($this->classVersionMethods->getMethods() as $classVersionMethod) {
            if (version_compare($moduleVersion, $classVersionMethod->getVersion(), '>=')) {
                continue;
            }
            $this->{$classVersionMethod->getMethod()}();
        }
    }
}

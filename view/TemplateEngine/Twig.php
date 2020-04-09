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

namespace DannyDamsky\DevTools\View\TemplateEngine;

use DannyDamsky\DevTools\Helper\Config;
use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\TemplateEngine\Php;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;
use function array_shift;
use function class_exists;
use function func_get_args;
use function str_replace;

/**
 * Class Twig
 *
 * Adds support for the Twig (Version 2.0.0 and above) templating engine.
 *
 * @package DannyDamsky\DevTools\View\TemplateEngine
 * @since 2020-03-26
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
class Twig extends Php
{
    /** @var string */
    private const TWIG_CACHE_DIR = 'dannydamsky_devtools_twig_cache';

    /** @var Config */
    protected $_configHelper;

    /** @var DirectoryList */
    protected $_directoryList;

    /** @var string */
    protected $_baseDir;

    /** @var string */
    protected $_cacheDir;

    /** @var Environment */
    protected $_twig;

    /**
     * Construct required variables for the render function.
     *
     * @throws FileSystemException
     */
    private function _construct(): void
    {
        $this->_configHelper = $this->_helperFactory->get(Config::class);
        $this->_directoryList = $this->_helperFactory->get(DirectoryList::class);
        $this->_baseDir = $this->_directoryList->getPath(DirectoryList::ROOT) . DIRECTORY_SEPARATOR;
        $this->_cacheDir = $this->_directoryList->getPath(DirectoryList::VAR_DIR) . DIRECTORY_SEPARATOR . self::TWIG_CACHE_DIR;
        $this->_twig = $this->getTwigEnvironment();
    }

    /**
     * Get the environment instance of the twig templating engine.
     *
     * @return Environment|null
     */
    private function getTwigEnvironment(): ?Environment
    {
        if (class_exists(Environment::class)) {
            $loader = new FilesystemLoader($this->_baseDir);
            return new Environment($loader);
        }
        return null;
    }

    /**
     * @inheritDoc
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    final public function render(BlockInterface $block, $fileName, array $dictionary = [])
    {
        $this->_construct();
        if ($this->_twig === null) {
            return parent::render($block, $fileName, $dictionary);
        }
        return $this->_render($block, $fileName, $dictionary);
    }

    /**
     * Render output
     *
     * Include the named PHTML template using the given block as the $this
     * reference, though only public methods will be accessible.
     *
     * @param BlockInterface $block
     * @param string $fileName
     * @param array $dictionary
     * @return string
     * @throws Exception
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    protected function _render(BlockInterface $block, $fileName, array $dictionary = [])
    {
        $this->initializeTwig();
        $this->extendTwigFunctionality();
        $this->_currentBlock = $block;
        $template = str_replace($this->_baseDir, '', $fileName);
        $dictionary['block'] = $block;
        return $this->_twig->load($template)->render($dictionary);
    }

    /**
     * Initialize twig state according to the module configuration.
     */
    protected function initializeTwig(): void
    {
        $this->initializeTwigCache();
        $this->initializeTwigDebug();
        $this->initializeTwigAutoRecompile();
        $this->initializeTwigStrictVariables();
        $this->initializeTwigCharset();
    }

    /**
     * Initialize twig cache state according to the module configuration.
     */
    protected function initializeTwigCache(): void
    {
        if ($this->_configHelper->isTwigCacheEnabled()) {
            $this->_twig->setCache($this->_cacheDir);
        }
    }

    /**
     * Initialize twig debug state according to the module configuration.
     */
    protected function initializeTwigDebug(): void
    {
        if ($this->_configHelper->isTwigDebugEnabled()) {
            $this->_twig->enableDebug();
        } else {
            $this->_twig->disableDebug();
        }
    }

    /**
     * Initialize twig auto-recompile state according to the module configuration.
     */
    protected function initializeTwigAutoRecompile(): void
    {
        if ($this->_configHelper->isTwigAutoRecompileEnabled()) {
            $this->_twig->enableAutoReload();
        } else {
            $this->_twig->disableAutoReload();
        }
    }

    /**
     * Initialize twig strict variables state according to the module configuration.
     */
    protected function initializeTwigStrictVariables(): void
    {
        if ($this->_configHelper->isTwigStrictVariablesEnabled()) {
            $this->_twig->enableStrictVariables();
        } else {
            $this->_twig->disableStrictVariables();
        }
    }

    /**
     * Initialize twig charset according to the module configuration.
     */
    protected function initializeTwigCharset(): void
    {
        $this->_twig->setCharset($this->_configHelper->getTwigCharset());
    }

    /**
     * Extend the functionality of Twig by adding a bunch of useful features.
     */
    protected function extendTwigFunctionality(): void
    {
        $this->_twig->addFunction(new TwigFunction('helper', [$this, 'helper']));
        $this->_twig->addFunction(new TwigFunction('layoutBlock', [$this, '__call']));
        $this->_twig->addFunction(new TwigFunction('get*', [$this, 'catchGet']));
        $this->_twig->addFunction(new TwigFunction('isset', [$this, '__isset']));
        $this->_twig->addFilter(new TwigFilter('trans', '__'));
        $this->_twig->addFilter(new TwigFilter('prettify', 'prettify'));
        $this->_twig->addExtension(new DebugExtension());
    }

    /**
     * @return mixed
     */
    public function catchGet()
    {
        $args = func_get_args();
        $name = array_shift($args);
        return $this->__call("get{$name}", $args);
    }
}

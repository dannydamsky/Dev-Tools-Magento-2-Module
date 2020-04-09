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

namespace DannyDamsky\DevTools\Traits;

use Closure;
use function array_merge;
use function json_encode;

/**
 * Trait BlockTrait
 *
 * A trait that is used by block classes
 * to add functionality to PHP templates.
 *
 * @package DannyDamsky\DevTools\Traits
 * @since 2020-03-24
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
trait BlockTrait
{
    private $xMagentoInitArray = [];

    /**
     * @inheritDoc
     */
    protected function _beforeToHtml()
    {
        $this->assign([
            'escapeHtml' => Closure::fromCallable([$this, 'escapeHtml']),
            'escapeHtmlAttr' => Closure::fromCallable([$this, 'escapeHtmlAttr']),
            'escapeUrl' => Closure::fromCallable([$this, 'escapeUrl']),
            'escapeCss' => Closure::fromCallable([$this, 'escapeCss']),
            'escapeJs' => Closure::fromCallable([$this, 'escapeJs']),
            'trans' => Closure::fromCallable('__')
        ]);
        return parent::_beforeToHtml();
    }

    /**
     * Add a javascript component to the text/x-magento-init script.
     *
     * @param string $componentName - The name of the component as used by require.js
     * @param string $elementSelector - A jQuery selector of the highest level element that should work with the component.
     * @param array $extraParams - Extra data to pass to the script.
     */
    public function addXMagentoInitComponent(string $componentName, string $elementSelector = '*', array $extraParams = []): void
    {
        if (!isset($this->xMagentoInitArray[$elementSelector])) {
            $this->xMagentoInitArray[$elementSelector]['Magento_Ui/js/core/app']['components'][$componentName] = [];
        }
        $componentsArray = &$this->xMagentoInitArray[$elementSelector]['Magento_Ui/js/core/app']['components'];
        if (!isset($componentsArray[$componentName])) {
            $componentsArray[$componentName] = [];
        }
        $componentArray = &$componentsArray[$componentName];
        $componentArray = array_merge($componentArray, ['component' => $componentName], $extraParams);
    }

    /**
     * Render a script tag for the text/x-magento-init script.
     *
     * @param string|null $componentName - The name of the component as used by require.js
     * @param string $elementSelector - A jQuery selector of the highest level element that should work with the component.
     * @param array $extraParams - Extra data to pass to the script.
     * @return string
     */
    public function xMagentoInit(?string $componentName = null, string $elementSelector = '*', array $extraParams = []): string
    {
        if ($componentName !== null) {
            $this->addXMagentoInitComponent($componentName, $elementSelector, $extraParams);
        }
        $data = json_encode($this->xMagentoInitArray, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
        return "<script type='text/x-magento-init'>{$data}</script>";
    }
}

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

namespace DannyDamsky\DevTools\Cron;

use DannyDamsky\DevTools\Exception\IOException;
use DannyDamsky\DevTools\Helper\Config;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\DirectoryList;
use function basename;
use function date;
use function glob;
use function gzcompress_file;
use function is_dir;
use function rename;
use function rglob;
use function rm_rf;
use function strtotime;

/**
 * Class LogRotate
 *
 * A class that manages rotating log files.
 *
 * @package DannyDamsky\DevTools\Cron
 * @since 2020-03-25
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
class LogRotate
{
    /**
     * @var Config
     */
    protected $_configHelper;

    /**
     * @var DirectoryList
     */
    protected $_directoryList;

    /**
     * LogRotate constructor.
     * @param Config $configHelper
     * @param DirectoryList $directoryList
     */
    public function __construct(
        Config $configHelper,
        DirectoryList $directoryList
    )
    {
        $this->_configHelper = $configHelper;
        $this->_directoryList = $directoryList;
    }

    /**
     * Execute the logs cleanup.
     *
     * @throws FileSystemException
     */
    public function execute(): void
    {
        if (!$this->_configHelper->isLogRotationEnabled()) {
            return;
        }
        /*
         * RETRIEVE PATHS
         */
        $dsp = DIRECTORY_SEPARATOR;
        $varPath = $this->_directoryList->getPath('var');
        $logsPath = $this->_directoryList->getPath('log');
        $logRotatorPath = "{$varPath}{$dsp}dannydamsky_devtools_log_rotation";

        /*
         * CREATE LOG ROTATION PATH IF NOT EXISTS
         */
        if (!is_dir($logRotatorPath) && !mkdir($logRotatorPath, 0777) && !is_dir($logRotatorPath)) {
            throw IOException::couldNotCreateDirectory($logRotatorPath);
        }

        /*
         * MOVE THE var/log FOLDER TO THE NEW LOG ROTATION PATH
         */
        $today = date('Y-m-d');
        $newPath = "{$logRotatorPath}{$dsp}{$today}";
        if (is_dir($newPath)) {
            return;
        }
        rename($logsPath, $newPath);

        /*
         * RECREATE THE var/log FOLDER
         */
        if (!mkdir($logsPath, 0777) && !is_dir($logsPath)) {
            throw IOException::couldNotCreateDirectory($logsPath);
        }

        /*
         * COMPRESS THE LOG FILES
         */
        if ($this->_configHelper->isLogRotationCompressionEnabled()) {
            foreach (rglob("{$newPath}{$dsp}*.log", GLOB_NOSORT) as $logFile) {
                gzcompress_file($logFile);
            }
        }

        /*
         * DELETE OLD ROTATION LOG FOLDERS
         */
        $days = $this->_configHelper->getLogRotationDays();
        $oldDate = date('Y-m-d', strtotime("$today - $days days"));
        foreach (glob("{$logRotatorPath}{$dsp}*", GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $folderDate = basename($dir);
            if ($folderDate <= $oldDate) {
                rm_rf($dir);
            }
        }
    }
}

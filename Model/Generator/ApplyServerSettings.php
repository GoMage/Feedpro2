<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.2.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Generator;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ApplyServerSettings
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $memoryLimit = $this->scopeConfig->getValue('gomage_feed/server/memory_limit');
        $fileSize = $this->scopeConfig->getValue('gomage_feed/server/upload_max_filesize');
        $postSize = $this->scopeConfig->getValue('gomage_feed/server/post_max_size');
        $timeLimit = $this->scopeConfig->getValue('gomage_feed/server/time_limit');

        if ($memoryLimit) {
            ini_set("memory_limit", $memoryLimit . "M");
        }
        if ($fileSize) {
            ini_set("upload_max_filesize", $fileSize . "M");
        }
        if ($postSize) {
            ini_set("post_max_size", $postSize . "M");
        }

        set_time_limit((int)$timeLimit);
    }
}

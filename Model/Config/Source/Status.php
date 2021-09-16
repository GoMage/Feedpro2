<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2021 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.4.2
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Config\Source;

class Status implements \Magento\Framework\Option\ArrayInterface
{

    const NEWBORN = 0;
    const IN_PROGRESS = 1;
    const COMPLETED = 2;
    const FAILED = 3;
    const STOPPED = 4;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::NEWBORN, 'label' => __('New')],
            ['value' => self::IN_PROGRESS, 'label' => __('In progress')],
            ['value' => self::COMPLETED, 'label' => __('Completed')],
            ['value' => self::FAILED, 'label' => __('Failed')],
            ['value' => self::STOPPED, 'label' => __('Stopped')],
        ];
    }

}

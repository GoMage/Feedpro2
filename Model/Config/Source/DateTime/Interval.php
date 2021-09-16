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

namespace GoMage\Feed\Model\Config\Source\DateTime;

class Interval implements \Magento\Framework\Option\ArrayInterface
{
    const EVERY_1_HOUR = 1;
    const EVERY_3_HOUR = 3;
    const EVERY_6_HOUR = 6;
    const EVERY_12_HOUR = 12;
    const EVERY_24_HOUR = 24;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::EVERY_1_HOUR, 'label' => __('every %1 hour', 1)],
            ['value' => self::EVERY_3_HOUR, 'label' => __('every %1 hour', 3)],
            ['value' => self::EVERY_6_HOUR, 'label' => __('every %1 hour', 6)],
            ['value' => self::EVERY_12_HOUR, 'label' => __('every %1 hour', 12)],
            ['value' => self::EVERY_24_HOUR, 'label' => __('every %1 hour', 24)],
        ];
    }

}

<?php

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
            ['value' => self::EVERY_1_HOUR, 'label' => __('every 1 hour')],
            ['value' => self::EVERY_3_HOUR, 'label' => __('every 3 hour')],
            ['value' => self::EVERY_6_HOUR, 'label' => __('every 6 hour')],
            ['value' => self::EVERY_12_HOUR, 'label' => __('every 12 hour')],
            ['value' => self::EVERY_24_HOUR, 'label' => __('every 24 hour')],
        ];
    }

}

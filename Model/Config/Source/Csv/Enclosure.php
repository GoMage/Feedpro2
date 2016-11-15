<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.0.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Config\Source\Csv;

class Enclosure implements \Magento\Framework\Option\ArrayInterface
{

    const DOUBLE_QUOTE = 0;
    const QUOTE = 1;
    const SPACE = 2;
    const SINGLE_SPACE = 3;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::DOUBLE_QUOTE, 'label' => __('"')],
            ['value' => self::QUOTE, 'label' => __("'")],
            ['value' => self::SPACE, 'label' => __('space - CSV format')],
            ['value' => self::SINGLE_SPACE, 'label' => __('space - without double space')],
        ];
    }

    /**
     * @param int $enclosure
     * @return string
     */
    public function getSymbol($enclosure)
    {
        switch (intval($enclosure)) {
            case (self::QUOTE):
                return '"';
                break;
            case (self::SPACE):
                return ' ';
                break;
            case (self::SINGLE_SPACE):
                return ' ';
                break;
            case (self::DOUBLE_QUOTE):
            default:
                return '"';
                break;
        }
    }

}

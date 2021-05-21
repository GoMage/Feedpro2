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
 * @version      Release: 1.4.1
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
        $symbol = '"';
        switch ((int)$enclosure) {
            case (self::QUOTE):
                $symbol = '"';
                break;
            case (self::SPACE):
                $symbol = ' ';
                break;
            case (self::SINGLE_SPACE):
                $symbol = ' ';
                break;
            case (self::DOUBLE_QUOTE):
                $symbol = '"';
                break;
        }
        return $symbol;
    }

}

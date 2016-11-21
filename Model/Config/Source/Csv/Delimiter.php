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

class Delimiter implements \Magento\Framework\Option\ArrayInterface
{
    const COMMA = 0;
    const TAB = 1;
    const COLON = 2;
    const SPACE = 3;
    const VERTICAL_PIPE = 4;
    const SEMI_COLON = 5;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::COMMA, 'label' => __('Comma')],
            ['value' => self::TAB, 'label' => __('Tab')],
            ['value' => self::COLON, 'label' => __('Colon')],
            ['value' => self::SPACE, 'label' => __('Space')],
            ['value' => self::VERTICAL_PIPE, 'label' => __('Vertical pipe')],
            ['value' => self::SEMI_COLON, 'label' => __('Semi-colon')],
        ];
    }

    /**
     * @param int $delimiter
     * @return string
     */
    public function getSymbol($delimiter)
    {
        $symbol = ",";
        switch ((int)$delimiter) {
            case (self::TAB):
                $symbol = "\t";
                break;
            case (self::COLON):
                $symbol = ":";
                break;
            case (self::SPACE):
                $symbol = " ";
                break;
            case (self::VERTICAL_PIPE):
                $symbol = "|";
                break;
            case (self::SEMI_COLON):
                $symbol = ";";
                break;
            case (self::COMMA):
                $symbol = ",";
                break;
        }
        return $symbol;
    }

}

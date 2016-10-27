<?php

namespace GoMage\Feed\Model\Config\Source;

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
        switch (intval($delimiter)) {
            case (self::TAB):
                return "\t";
                break;
            case (self::COLON):
                return ":";
                break;
            case (self::SPACE):
                return " ";
                break;
            case (self::VERTICAL_PIPE):
                return "|";
                break;
            case (self::SEMI_COLON):
                return ";";
                break;
            case (self::COMMA):
            default:
                return ",";
                break;
        }
    }

}

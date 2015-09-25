<?php

namespace GoMage\Feed\Model\Config\Source;

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

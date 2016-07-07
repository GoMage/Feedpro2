<?php

namespace GoMage\Feed\Model\Config\Source\Mapping;

class Output implements \Magento\Framework\Option\ArrayInterface
{

    const DEFAULTS = 0;
    const INTEGER = 1;
    const FLOAT = 2;
    const STRIP_TAGS = 3;
    const SPECIAL_ENCODE = 4;
    const SPECIAL_DECODE = 5;
    const DELETE_SPACE = 6;
    const BIG_TO_SMALL = 7;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::DEFAULTS, 'label' => __('Default')],
            ['value' => self::INTEGER, 'label' => __('Integer')],
            ['value' => self::FLOAT, 'label' => __('Float')],
            ['value' => self::STRIP_TAGS, 'label' => __('Strip Tags')],
            ['value' => self::SPECIAL_ENCODE, 'label' => __('Encode special chars')],
            ['value' => self::SPECIAL_DECODE, 'label' => __('Decode special chars')],
            ['value' => self::DELETE_SPACE, 'label' => __('Delete Space')],
            ['value' => self::BIG_TO_SMALL, 'label' => __('Big to small')],
        ];
    }

}

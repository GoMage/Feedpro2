<?php

namespace GoMage\Feed\Model\Config\Source\Filter;

class Type implements \Magento\Framework\Option\ArrayInterface
{

    const TOGETHER = 0;
    const SPLIT = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::TOGETHER, 'label' => __('Together')],
            ['value' => self::SPLIT, 'label' => __('Split')],
        ];
    }

}

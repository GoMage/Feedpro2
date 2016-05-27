<?php

namespace GoMage\Feed\Model\Config\Source\Filter;

class Condition implements \Magento\Framework\Option\ArrayInterface
{

    const EQ = 'eq';
    const NEQ = 'neq';
    const GT = 'gt';
    const LT = 'lt';
    const GTEQ = 'gteq';
    const LTEQ = 'lteq';
    const LIKE = 'like';
    const NLIKE = 'nlike';
    const NIN = 'nin';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::EQ, 'label' => __('equal')],
            ['value' => self::NEQ, 'label' => __('not equal')],
            ['value' => self::GT, 'label' => __('greater than')],
            ['value' => self::LT, 'label' => __('less than')],
            ['value' => self::GTEQ, 'label' => __('greater than or equal to')],
            ['value' => self::LTEQ, 'label' => __('less than or equal to')],
            ['value' => self::LIKE, 'label' => __('like')],
            ['value' => self::NLIKE, 'label' => __('not like')],
            ['value' => self::NIN, 'label' => __('xor')],
        ];
    }

}

<?php

namespace GoMage\Feed\Model\Config\Source\Mapping;

class Type implements TypeInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::ATTRIBUTE, 'label' => __('Attribute')],
            ['value' => self::PARENT_ATTRIBUTE, 'label' => __('Parent Attribute')],
            ['value' => self::STATIC_VALUE, 'label' => __('Static Value')],
        ];
    }
    
}

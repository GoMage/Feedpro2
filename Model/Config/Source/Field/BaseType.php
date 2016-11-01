<?php

namespace GoMage\Feed\Model\Config\Source\Field;

class BaseType implements TypeInterface
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
            ['value' => self::DYNAMIC_ATTRIBUTE, 'label' => __('Dynamic Attribute')],
        ];
    }

}

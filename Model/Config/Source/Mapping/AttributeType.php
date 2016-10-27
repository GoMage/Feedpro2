<?php

namespace GoMage\Feed\Model\Config\Source\Mapping;

class AttributeType implements TypeInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::STATIC_VALUE, 'label' => __('Static Value')],
            ['value' => self::ATTRIBUTE_SET, 'label' => __('Attribute')],
            ['value' => self::PERCENT, 'label' => __('Percent from value')],
            ['value' => self::CONFIGURABLE_VALUES, 'label' => __('Configurable values')],
        ];
    }

}
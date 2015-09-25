<?php

namespace GoMage\Feed\Model\Config\Source\Mapping;

class ExtendedType implements TypeInterface
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
            ['value' => self::EMPTY_PARENT_ATTRIBUTE, 'label' => __('If Parent attr. is empty')],
            ['value' => self::EMPTY_CHILD_ATTRIBUTE, 'label' => __('If Child attr. is empty')],
        ];
    }

}

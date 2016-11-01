<?php

namespace GoMage\Feed\Model\Config\Source\Field;

class ExtendedType extends BaseType
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return array_merge(parent::toOptionArray(), [
                ['value' => self::EMPTY_PARENT_ATTRIBUTE, 'label' => __('If Parent attr. is empty')],
                ['value' => self::EMPTY_CHILD_ATTRIBUTE, 'label' => __('If Child attr. is empty')]]
        );
    }

}

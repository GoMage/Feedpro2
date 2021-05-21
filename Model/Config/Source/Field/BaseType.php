<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2021 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.4.1
 * @since        Class available since Release 1.0.0
 */

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
            ['value' => self::PARENT_DYNAMIC_ATTRIBUTE, 'label' => __('Parent Dynamic Attribute')],
        ];
    }

}

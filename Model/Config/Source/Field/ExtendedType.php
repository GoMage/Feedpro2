<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.1.1
 * @since        Class available since Release 1.0.0
 */

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

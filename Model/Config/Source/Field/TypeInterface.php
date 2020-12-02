<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.3.2
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Config\Source\Field;

use Magento\Framework\Option\ArrayInterface;

interface TypeInterface extends ArrayInterface
{
    const ATTRIBUTE = 0;
    const PARENT_ATTRIBUTE = 1;
    const STATIC_VALUE = 2;
    const EMPTY_PARENT_ATTRIBUTE = 3;
    const EMPTY_CHILD_ATTRIBUTE = 4;
    const ATTRIBUTE_SET = 5;
    const PERCENT = 6;
    const CONFIGURABLE_VALUES = 7;
    const DYNAMIC_ATTRIBUTE = 8;
    const PARENT_DYNAMIC_ATTRIBUTE = 9;
}

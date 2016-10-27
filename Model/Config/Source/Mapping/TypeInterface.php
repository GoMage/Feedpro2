<?php

namespace GoMage\Feed\Model\Config\Source\Mapping;

use  \Magento\Framework\Option\ArrayInterface;

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
}
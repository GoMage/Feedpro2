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

namespace GoMage\Feed\Model\Mapper;

class StaticValue implements MapperInterface
{

    /**
     * @var string
     */
    protected $_value;

    public function __construct(
        $value
    ) {
        $this->_value = $value;
    }

    /**
     * @param  \Magento\Framework\DataObject $object
     * @return mixed
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        return $this->_value;
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return [];
    }
}

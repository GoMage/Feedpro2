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
 * @version      Release: 1.4.2
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Feed;


class Field
{
    /**
     * @var string
     */
    protected $_type;

    /**
     * @var string
     */
    protected $_value;

    /**
     * @var \GoMage\Feed\Model\Mapper\MapperInterface
     */
    protected $_mapper;


    public function __construct($type, $value, $additionalData, \GoMage\Feed\Model\Mapper\Factory $mapperFactory)
    {
        $this->_type   = $type;
        $this->_value  = $value;
        $this->_mapper = $mapperFactory->create($type, $value, $additionalData);
    }

    /**
     * @param  \Magento\Framework\DataObject $object
     * @return mixed
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        return $this->_mapper->map($object);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return $this->_mapper->getUsedAttributes();
    }

    /**
     * @return string
     */
    public function getType(){
        return $this->_type;
    }
}

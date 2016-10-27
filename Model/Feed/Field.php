<?php

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


    public function __construct($type, $value, \GoMage\Feed\Model\Mapper\Factory $mapperFactory)
    {
        $this->_type   = $type;
        $this->_value  = $value;
        $this->_mapper = $mapperFactory->create($type, $value);
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

}
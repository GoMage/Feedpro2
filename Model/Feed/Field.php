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

    /**
     * @param $type
     * @param $value
     */
    public function __construct($type, $value, \GoMage\Feed\Model\Mapper\Factory $mapperFactory)
    {
        $this->_type   = $type;
        $this->_value  = $value;
        $this->_mapper = $mapperFactory->create($type, $value);
    }

    /**
     * @param  $object
     * @return mixed
     */
    public function map($object)
    {
        return $this->_mapper->map($object);
    }

    /**
     * @return \GoMage\Feed\Model\Mapper\MapperInterface
     */
    public function getMapper()
    {
        return $this->_mapper;
    }

}
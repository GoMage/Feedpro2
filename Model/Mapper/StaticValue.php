<?php
namespace GoMage\Feed\Model\Mapper;

class StaticValue implements MapperInterface
{

    /**
     * @var string
     */
    protected $_value;

    public function __construct(
        $field_value
    ) {
        $this->_value = $field_value;
    }

    /**
     * @param  $object
     * @return mixed
     */
    public function map($object)
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
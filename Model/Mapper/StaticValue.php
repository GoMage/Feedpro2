<?php
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
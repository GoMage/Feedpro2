<?php
namespace GoMage\Feed\Model\Mapper;

class AttributePercent extends Attribute implements MapperInterface
{

    /**
     * @var float
     */
    protected $_percent;

    public function __construct(
        $value,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository
    ) {
        $this->_percent = floatval($value['percent']);
        parent::__construct($value['code'], $attributeRepository);
    }

    /**
     * @param  \Magento\Framework\DataObject $object
     * @return float
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        return floatval(parent::map($object)) * $this->_percent / 100;
    }

}
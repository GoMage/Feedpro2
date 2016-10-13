<?php
namespace GoMage\Feed\Model\Mapper;

use Magento\Catalog\Api\ProductAttributeRepositoryInterface;

class Attribute implements MapperInterface
{

    /**
     * @var string
     */
    protected $_code;

    /**
     * @var \Magento\Catalog\Api\Data\ProductAttributeInterface
     */
    protected $_attribute;


    public function __construct(
        $field_value,
        ProductAttributeRepositoryInterface $attributeRepository
    ) {
        $this->_code      = $field_value;
        $this->_attribute = $attributeRepository->get($this->_code);
    }

    /**
     * @param  $object
     * @return mixed
     */
    public function map($object)
    {
        return $this->_attribute->getFrontendModel() == 'Magento\Catalog\Model\Product\Attribute\Frontend\Image' ?
            $this->_attribute->getFrontend()->getUrl($object) :
            $this->_attribute->getFrontend()->getValue($object);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return [$this->_code];
    }
}
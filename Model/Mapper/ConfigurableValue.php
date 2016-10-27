<?php
namespace GoMage\Feed\Model\Mapper;

use Magento\Catalog\Api\ProductAttributeRepositoryInterface;

class ConfigurableValue implements MapperInterface
{

    /**
     * @var string
     */
    protected $_prefix;

    /**
     * @var string
     */
    protected $_code;

    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    protected $_configurable;

    /**
     * @var \Magento\Catalog\Api\Data\ProductAttributeInterface
     */
    protected $_attribute;


    public function __construct(
        $value,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurable,
        ProductAttributeRepositoryInterface $attributeRepository
    ) {
        $this->_prefix       = $value['prefix'];
        $this->_code         = $value['code'];
        $this->_configurable = $configurable;
        $this->_attribute    = $attributeRepository->get($this->_code);
    }

    /**
     * @param  \Magento\Framework\DataObject $object
     * @return string
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        if ($object->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            $options = $this->_configurable->getAttributeOptions($this->_attribute, $object->getId());
            $options = array_map(function ($option) {
                return $option['option_title'];
            }, $options
            );
            $value   = implode(',', array_unique(array_filter($options)));
            return $value ? $this->_prefix . $value : '';
        }
        return '';
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return [];
    }
}
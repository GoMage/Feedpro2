<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.1.1
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Mapper;

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
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository
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
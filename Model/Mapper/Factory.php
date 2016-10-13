<?php

namespace GoMage\Feed\Model\Mapper;

use GoMage\Feed\Model\Config\Source\Mapping\TypeInterface;

class Factory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var array
     */
    protected $_customMappers = [
        TypeInterface::ATTRIBUTE => [
            'category_subcategory' => 'GoMage\Feed\Model\Mapper\Attribute\CategorySubcategory',
            'id'                   => 'GoMage\Feed\Model\Mapper\Attribute\ProductId',

            //TODO: only for restaurantsupply.com
            'free_shipping_feed'   => 'GoMage\Feed\Model\Mapper\Attribute\FreeShipping',
            'url_key'              => 'GoMage\Feed\Model\Mapper\Attribute\UrlKey',
            'weight'               => 'GoMage\Feed\Model\Mapper\Attribute\Weight',
            'small_image'          => 'GoMage\Feed\Model\Mapper\Attribute\SmallImage',
        ]
    ];

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * @param  string $fieldType
     * @param  string $fieldValue
     * @return \GoMage\Feed\Model\Mapper\MapperInterface
     */
    public function create($fieldType, $fieldValue)
    {
        $className = $this->_getCustomMapper($fieldType, $fieldValue);

        if (!$className) {
            switch ($fieldType) {
                case TypeInterface::ATTRIBUTE:
                    $className = 'GoMage\Feed\Model\Mapper\Attribute';
                    break;
                case TypeInterface::STATIC_VALUE:
                    $className = 'GoMage\Feed\Model\Mapper\StaticValue';
                    break;
            }
        }

        $arguments = ['field_value' => $fieldValue];

        return $this->_objectManager->create($className, $arguments);
    }

    /**
     * @param  string $fieldType
     * @param  string $fieldValue
     * @return string
     */
    protected function _getCustomMapper($fieldType, $fieldValue)
    {
        if (isset($this->_customMappers[$fieldType][$fieldValue])) {
            return $this->_customMappers[$fieldType][$fieldValue];
        }
        return false;
    }

}

<?php

namespace GoMage\Feed\Model\Mapper;

use GoMage\Feed\Model\Config\Source\Field\TypeInterface;

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
            'product_url'          => 'GoMage\Feed\Model\Mapper\Attribute\ProductUrl',
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
     * @param  string $type
     * @param  string $value
     * @return \GoMage\Feed\Model\Mapper\MapperInterface
     */
    public function create($type, $value)
    {
        $className = $this->_getCustomMapper($type, $value);

        if (!$className) {
            switch ($type) {
                case TypeInterface::ATTRIBUTE:
                    $className = 'GoMage\Feed\Model\Mapper\Attribute';
                    break;
                case TypeInterface::STATIC_VALUE:
                    $className = 'GoMage\Feed\Model\Mapper\StaticValue';
                    break;
                case TypeInterface::PERCENT:
                    $className = 'GoMage\Feed\Model\Mapper\AttributePercent';
                    break;
                case TypeInterface::ATTRIBUTE_SET:
                    $className = 'GoMage\Feed\Model\Mapper\AttributeSet';
                    break;
                case TypeInterface::CONFIGURABLE_VALUES:
                    $className = 'GoMage\Feed\Model\Mapper\ConfigurableValue';
                    break;
                case TypeInterface::DYNAMIC_ATTRIBUTE:
                    $className = 'GoMage\Feed\Model\Mapper\DynamicAttribute';
                    break;
            }
        }

        return $this->_objectManager->create($className, ['value' => $value]);
    }

    /**
     * @param  string $type
     * @param  string $value
     * @return string
     */
    protected function _getCustomMapper($type, $value)
    {
        if (isset($this->_customMappers[$type][$value])) {
            return $this->_customMappers[$type][$value];
        }
        return false;
    }

}

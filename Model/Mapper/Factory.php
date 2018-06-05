<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.0.0
 * @since        Class available since Release 1.0.0
 */

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
    protected $_customMappers;


    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        $customMappers = []
    ) {
        $this->_objectManager = $objectManager;
        $this->_customMappers = $customMappers;
    }

    /**
     * @param  string $type
     * @param  string $value
     * @return \GoMage\Feed\Model\Mapper\MapperInterface
     */
    public function create($type, $value)
    {
        $className = $this->_getCustomMapper($value);
        if (!$className) {
            $className = $this->_getMapper($type);
        }
        return $this->_objectManager->create($className, ['value' => $value]);
    }

    /**
     * @param  string $value
     * @return bool|string
     */
    protected function _getCustomMapper($value)
    {
        if (!is_array($value) && isset($this->_customMappers[$value])) {
            return $this->_customMappers[$value];
        }
        return false;
    }

    /**
     * @param  int $type
     * @return string
     */
    protected function _getMapper($type)
    {
        switch ($type) {
            case TypeInterface::ATTRIBUTE:
                $className = 'GoMage\Feed\Model\Mapper\Attribute';
                break;
            case TypeInterface::PARENT_ATTRIBUTE:
                $className = 'GoMage\Feed\Model\Mapper\ParentAttribute';
                break;
            case TypeInterface::EMPTY_PARENT_ATTRIBUTE:
                $className = 'GoMage\Feed\Model\Mapper\EmptyParentAttribute';
                break;
            case TypeInterface::EMPTY_CHILD_ATTRIBUTE:
                $className = 'GoMage\Feed\Model\Mapper\EmptyChildAttribute';
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
        return $className;
    }

    /**
     * @return array
     */
    public function getCustomMappers()
    {
        return $this->_customMappers;
    }

}

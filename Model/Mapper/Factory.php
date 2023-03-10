<?php
declare(strict_types=1);
/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2021 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.4.2
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
    public function create($type, $value, $additionalData)
    {
        $className = $this->_getCustomMapper($value);
        if (!$className) {
            $className = $this->_getMapper($type);
        }
        return $this->_objectManager->create($className, ['value' => $value, 'type' => $type, 'additionalData' => $additionalData]);
    }

    /**
     * @param $value
     * @return false|mixed
     */
    protected function _getCustomMapper($value)
    {
        if (!is_array($value) && isset($this->_customMappers[$value])) {
            return $this->_customMappers[$value];
        }
        if (is_string($value)) {
            $ar = explode(',', $value);
            if (!empty($ar) && $ar[0]==='msiStock') {
                return $this->_customMappers['msiStock'];
            }
            if (!empty($ar) && $ar[0]==='msiSource') {
                return $this->_customMappers['msiSource'];
            }
        }
        return false;
    }

    /**
     * @param  int $type
     * @return string
     */
    protected function _getMapper($type)
    {
        $className = null;

        switch ($type) {
            case TypeInterface::ATTRIBUTE:
                $className = Attribute::class;
                break;
            case TypeInterface::PARENT_ATTRIBUTE:
                $className = ParentAttribute::class;
                break;
            case TypeInterface::EMPTY_PARENT_ATTRIBUTE:
                $className = EmptyParentAttribute::class;
                break;
            case TypeInterface::EMPTY_CHILD_ATTRIBUTE:
                $className = EmptyChildAttribute::class;
                break;
            case TypeInterface::STATIC_VALUE:
                $className = StaticValue::class;
                break;
            case TypeInterface::PERCENT:
                $className = AttributePercent::class;
                break;
            case TypeInterface::ATTRIBUTE_SET:
                $className = AttributeSet::class;
                break;
            case TypeInterface::CONFIGURABLE_VALUES:
                $className = ConfigurableValue::class;
                break;
            case TypeInterface::DYNAMIC_ATTRIBUTE:
                $className = DynamicAttribute::class;
                break;
            case TypeInterface::PARENT_DYNAMIC_ATTRIBUTE:
                $className = ParentDynamicAttribute::class;
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

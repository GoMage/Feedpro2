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
 * @version      Release: 1.1.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Mapper;

class AttributeSet implements MapperInterface
{

    /**
     * @var \GoMage\Feed\Model\Collection
     */
    protected $_fields;

    public function __construct(
        $value,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_fields = $objectManager->create('GoMage\Feed\Model\Collection');

        foreach ($value as $data) {
            $field = $objectManager->create('GoMage\Feed\Model\Attribute\Field', [
                    'type'   => \GoMage\Feed\Model\Config\Source\Field\TypeInterface::ATTRIBUTE,
                    'value'  => $data['code'],
                    'prefix' => $data['prefix']
                ]
            );
            $this->_fields->add($field);
        }
    }

    /**
     * @param  \Magento\Framework\DataObject $object
     * @return mixed
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        $values = array_map(function (\GoMage\Feed\Model\Attribute\Field $field) use ($object) {
            return $field->map($object);
        }, iterator_to_array($this->_fields)
        );

        return implode('', $values);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        $attributes = [];
        /** @var \GoMage\Feed\Model\Attribute\Field $field */
        foreach ($this->_fields as $field) {
            $attributes = array_merge($attributes, $field->getUsedAttributes());
        }
        return $attributes;
    }
}
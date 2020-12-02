<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.3.2
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Feed;

use GoMage\Feed\Model\Config\Source\Field\TypeInterface;

class Row
{

    /**
     * @var string
     */
    protected $_name;

    /**
     * @var int
     */
    protected $_limit;

    /**
     * @var \GoMage\Feed\Model\Collection
     */
    protected $_outputs;

    /**
     * @var \GoMage\Feed\Model\Collection
     */
    protected $_fields;


    public function __construct(
        \GoMage\Feed\Model\Feed\Row\Data $rowData,
        \GoMage\Feed\Model\Output\Factory $outputFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->_name = $rowData->getName();
        $this->_limit = (int)$rowData->getLimit();

        $this->_outputs = $objectManager->create('GoMage\Feed\Model\Collection');
        foreach ($rowData->getOutput() as $value) {
            /** @var \GoMage\Feed\Model\Output\OutputInterface $output */
            $output = $outputFactory->get($value);
            $this->_outputs->add($output);
        }

        $this->_fields = $objectManager->create('GoMage\Feed\Model\Collection');
        foreach ($rowData->getFields() as $data) {
            /** @var \GoMage\Feed\Model\Feed\Field $field */
            $field = $objectManager->create('GoMage\Feed\Model\Feed\Field', $data);
            $this->_fields->add($field);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return mixed
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        $array = array_map(function (\GoMage\Feed\Model\Feed\Field $field) use ($object) {
            return $this->getReformattedValue($field, $object);
        }, iterator_to_array($this->_fields)
        );
        return implode('', $array);
    }

    /**
     * @param  $value
     * @return mixed
     */
    protected function format($value)
    {
        foreach ($this->_outputs as $output) {
            /** @var \GoMage\Feed\Model\Output\OutputInterface $output */
            $value = $output->format($value);
        }
        if ($this->_limit) {
            return substr($value, 0, $this->_limit);
        }
        return $value;
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        $attributes = [];
        /** @var \GoMage\Feed\Model\Feed\Field $field */
        foreach ($this->_fields as $field) {
            $attributes = array_merge($attributes, $field->getUsedAttributes());
        }
        return $attributes;
    }

    /**
     *
     * @param $field
     * @param $object
     * @return mixed
     *
     */
    protected function getReformattedValue($field, $object)
    {
        if ($field->getType() != TypeInterface::STATIC_VALUE) {
            return $this->format($field->map($object));
        }
        return $field->map($object);
    }
}


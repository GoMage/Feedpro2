<?php

namespace GoMage\Feed\Model\Feed;

use GoMage\Feed\Model\Output\OutputInterface;

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
     * @var OutputInterface
     */
    protected $_output;

    /**
     * @var \GoMage\Feed\Model\Feed\Field\Collection
     */
    protected $_fields;


    public function __construct(
        \GoMage\Feed\Model\Feed\Row\Data $rowData,
        \GoMage\Feed\Model\Output\Factory $outputFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_name  = $rowData->getName();
        $this->_limit = intval($rowData->getLimit());

        $this->_output = $outputFactory->get(intval($rowData->getOutput()));
        $this->_fields = $objectManager->create('GoMage\Feed\Model\Feed\Field\Collection');

        foreach ($rowData->getFieldsData() as $fieldData) {
            /** @var \GoMage\Feed\Model\Feed\Field $field */
            $field = $objectManager->create('GoMage\Feed\Model\Feed\Field', $fieldData);
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
     * @param  $object
     * @return mixed
     */
    public function map($object)
    {
        $array = array_map(function ($field) use ($object) {
            return $field->map($object);
        }, $this->_fields->get()
        );

        $value = implode('', array_filter($array));

        return $this->format($value);
    }

    /**
     * @param  $value
     * @return mixed
     */
    protected function format($value)
    {
        $value = $this->_output->format($value);
        if ($this->_limit) {
            return substr($value, 0, $this->_limit);
        }
        return $value;
    }

    /**
     * @return \GoMage\Feed\Model\Feed\Field\Collection
     */
    public function getFields()
    {
        return $this->_fields;
    }

}

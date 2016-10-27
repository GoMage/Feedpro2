<?php

namespace GoMage\Feed\Model\Attribute;

class Row
{

    /**
     * @var \GoMage\Feed\Model\Collection
     */
    protected $_conditions;

    /**
     * @var \GoMage\Feed\Model\Feed\Field
     */
    protected $_field;

    public function __construct(
        \GoMage\Feed\Model\Attribute\Row\Data $rowData,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_conditions = $objectManager->create('GoMage\Feed\Model\Collection');

        foreach ($rowData->getConditions() as $data) {
            $conditionData = $objectManager->create('GoMage\Feed\Model\Attribute\Condition\Data', ['data' => $data]);
            $condition     = $objectManager->create('GoMage\Feed\Model\Attribute\Condition', ['conditionData' => $conditionData]);
            $this->_conditions->add($condition);
        }

        $this->_field = $objectManager->create('GoMage\Feed\Model\Feed\Field',
            [
                'type'  => $rowData->getType(),
                'value' => $rowData->getValue()
            ]
        );

    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return bool
     */
    public function verify(\Magento\Framework\DataObject $object)
    {
        foreach ($this->_conditions as $condition) {
            if (!$condition->verify($object)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return mixed
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        return $this->_field->map($object);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        $attributes = [];
        /** @var \GoMage\Feed\Model\Attribute\Condition $condition */
        foreach ($this->_conditions as $condition) {
            $attributes = array_merge($attributes, $condition->getUsedAttributes());
        }
        return array_merge($attributes, $this->_field->getUsedAttributes());
    }


}

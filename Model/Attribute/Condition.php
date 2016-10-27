<?php

namespace GoMage\Feed\Model\Attribute;

class Condition
{

    /**
     * @var \GoMage\Feed\Model\Feed\Field
     */
    protected $_field;

    /**
     * @var \GoMage\Feed\Model\Operator\OperatorInterface
     */
    protected $_operator;

    /**
     * @var string
     */
    protected $_value;

    public function __construct(
        \GoMage\Feed\Model\Attribute\Condition\Data $conditionData,
        \GoMage\Feed\Model\Operator\Factory $operatorFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_operator = $operatorFactory->get($conditionData->getOperator());
        $this->_field    = $objectManager->create('GoMage\Feed\Model\Feed\Field',
            [
                'type'  => \GoMage\Feed\Model\Config\Source\Mapping\TypeInterface::ATTRIBUTE,
                'value' => $conditionData->getCode()
            ]
        );
        $this->_value    = $conditionData->getValue();
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return bool
     */
    public function verify(\Magento\Framework\DataObject $object)
    {
        $testable = $this->_field->map($object);
        return $this->_operator->compare($testable, $this->_value);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return $this->_field->getUsedAttributes();
    }


}

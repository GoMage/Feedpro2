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
 * @version      Release: 1.2.0
 * @since        Class available since Release 1.0.0
 */

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

    /**
     * @var string
     */
    protected $attrCode;

    /**
     * @var \Magento\Eav\Api\AttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * Condition constructor.
     * @param Condition\Data $conditionData
     * @param \GoMage\Feed\Model\Operator\Factory $operatorFactory
     * @param \GoMage\Feed\Model\Feed\FieldFactory $fieldFactory
     * @param \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(
        \GoMage\Feed\Model\Attribute\Condition\Data $conditionData,
        \GoMage\Feed\Model\Operator\Factory $operatorFactory,
        \GoMage\Feed\Model\Feed\FieldFactory $fieldFactory,
        \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository,
        $additionalData
    ) {
        $this->_operator = $operatorFactory->get($conditionData->getOperator());
        $this->_field    = $fieldFactory->create(
            [
                'type'  => \GoMage\Feed\Model\Config\Source\Field\TypeInterface::ATTRIBUTE,
                'value' => $conditionData->getCode(),
                'additionalData' => $additionalData
            ]
        );
        $this->_value    = $conditionData->getValue();
        $this->attrCode  = $conditionData->getCode();
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return bool
     */
    public function verify(\Magento\Framework\DataObject $object)
    {
        $testable = $this->_field->map($object);
        try {
            $conditionAttribute = $this->attributeRepository->get(\Magento\Catalog\Model\Product::ENTITY, $this->attrCode);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $conditionAttribute = null;
        }
        $label = false;
        if ($conditionAttribute) {
            $frontendInput = $conditionAttribute->getFrontendInput();
            if ($frontendInput == 'select' || $frontendInput == 'multiselect') {
                foreach ($conditionAttribute->getOptions() as $option) {
                    if ($option->getValue() == $this->_value) {
                        $label = $option->getLabel();
                        break;
                    }
                }
            }

        }
        $value = (false !== $label) ? $label : $this->_value;
        return $this->_operator->compare($testable, $value);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return $this->_field->getUsedAttributes();
    }
}

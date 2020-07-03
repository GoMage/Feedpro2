<?php


namespace GoMage\Feed\Model\Rule\Condition;

use Magento\CatalogRule\Model\Rule\Condition\Product;
use Magento\Rule\Model\Condition\Context;

/**
 * Class Combine
 * @package GoMage\Feed\Model\Rule\Condition
 */
class Combine extends \Magento\Rule\Model\Condition\Combine
{
    /**
     * @var \Magento\CatalogRule\Model\Rule\Condition\ProductFactory
     */
    protected $_productFactory;

    /**
     * Combine constructor.
     * @param \Magento\CatalogRule\Model\Rule\Condition\ProductFactory $conditionFactory
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\CatalogRule\Model\Rule\Condition\ProductFactory $conditionFactory,
        Context $context,
        array $data = [])
    {
        $this->_productFactory = $conditionFactory;
        parent::__construct($context, $data);
        $this->setType(\GoMage\Feed\Model\Rule\Condition\Combine::class);
    }

    /**
     * Adding Product Type condition
     *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $productAttributes = $this->_productFactory->create()->loadAttributeOptions()->getAttributeOption();
        $attributes = [];
        foreach ($productAttributes as $code => $label) {
            $attributes[] = [
                'value' => 'Magento\CatalogRule\Model\Rule\Condition\Product|' . $code,
                'label' => $label,
            ];
        }
        $attributes[] = [
            'value' => 'GoMage\Feed\Model\Rule\Condition\Product|type_id',
            'label' => __('Product Type')
        ];
        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive(
            $conditions,
            [
                [
                    'value' => \Magento\CatalogRule\Model\Rule\Condition\Combine::class,
                    'label' => __('Conditions Combination'),
                ],
                ['label' => __('Product Attribute'), 'value' => $attributes]
            ]
        );
        return $conditions;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection
     * @return $this
     */
    public function collectValidatedAttributes($productCollection)
    {
        $correctedConditions = [];

        foreach ($this->getConditions() as $key => $condition) {
            /** @var Product|\Magento\CatalogRule\Model\Rule\Condition\Combine $condition */
            if ($condition->getAttribute() == 'type_id') {
                if ($condition->getOperator() == '==') {
                    $productCollection->addAttributeToFilter($condition->getAttribute(), ['eq' => $condition->getValue()]);
                } else {
                    $productCollection->addAttributeToFilter($condition->getAttribute(), ['neq' => $condition->getValue()]);
                }
            } else {
                $correctedConditions[] = $condition;
            }
        }

        $this->setConditions($correctedConditions); // change to new array in order to get rid product type_id condition

        foreach ($this->getConditions() as $condition) {
            /** @var Product|\Magento\CatalogRule\Model\Rule\Condition\Combine $condition */
            $condition->collectValidatedAttributes($productCollection);
        }

        return $this;
    }
}

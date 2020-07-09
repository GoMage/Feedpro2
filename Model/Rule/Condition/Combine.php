<?php

namespace GoMage\Feed\Model\Rule\Condition;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
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
        array $data = []
    ) {
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
            if ($code === 'quantity_and_stock_status') {
                $attributes[] = [
                    'value' => 'Magento\CatalogRule\Model\Rule\Condition\Product|' . $code,
                    'label' => 'Stock Status',
                ];
            } else {
                $attributes[] = [
                    'value' => 'Magento\CatalogRule\Model\Rule\Condition\Product|' . $code,
                    'label' => $label,
                ];
            }
        }
        $attributes[] = [
            'value' => 'GoMage\Feed\Model\Rule\Condition\Product|qty',
            'label' => __('Quantity')
        ];
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
     * @param Collection $productCollection
     * @return $this
     */
    public function collectValidatedAttributes($productCollection)
    {
        $correctedConditions = [];

        foreach ($this->getConditions() as $key => $condition) {
            /** @var Product|\Magento\CatalogRule\Model\Rule\Condition\Combine $condition */
            if ($condition->getAttribute() === 'type_id') {
                if ($condition->getOperator() == '==') {
                    $productCollection->addAttributeToFilter($condition->getAttribute(), ['eq' => $condition->getValue()]);
                } else {
                    $productCollection->addAttributeToFilter($condition->getAttribute(), ['neq' => $condition->getValue()]);
                }
            } elseif ($condition->getAttribute() === 'qty') {
                $this->addQtyFilter($productCollection, $condition);
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

    /**
     * @param Collection $productCollection
     * @param Product $condition
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addQtyFilter(Collection $productCollection, Product $condition)
    {
        $productCollection->joinField(
            'qty',
            'cataloginventory_stock_item',
            'qty',
            'product_id=entity_id',
            '{{table}}.stock_id=1',
            'left'
        );
        switch ($condition->getOperator()) {
            case '==':
                $operator = 'eq';
                break;
            case '!=':
                $operator = 'neq';
                break;
            case '>=':
                $operator = 'gteq';
                break;
            case '>':
                $operator = 'gt';
                break;
            case '<=':
                $operator = 'lteq';
                break;
            case '<':
                $operator = 'lt';
                break;
            case '()':
                $operator = 'in';
                break;
            case '!()':
                $operator = 'nin';
                break;
            default:
                $operator = false;
        }
        if (false !== $operator) {
            $productCollection->addAttributeToFilter('qty', [$operator => $condition->getValue()]);
        }
    }
}

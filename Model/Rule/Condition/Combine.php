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
namespace GoMage\Feed\Model\Rule\Condition;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\CatalogRule\Model\Rule\Condition\Product;
use Magento\Rule\Model\Condition\Context;

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
     * Adding custom conditions
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
                    'value' => 'GoMage\Feed\Model\Rule\Condition\Product|' . $code,
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
     * @param $productCollection
     * @return $this
     */
    public function collectValidatedAttributes($productCollection)
    {
        foreach ($this->getConditions() as $key => $condition) {
            /** @var Product|\Magento\CatalogRule\Model\Rule\Condition\Combine $condition */
            if ($condition->getAttribute() === 'qty') {
                $this->addQtyFilter($productCollection);
            } elseif ($condition->getAttribute() === 'quantity_and_stock_status') {
                $this->addStockStatusFilter($productCollection);
            }else{
                $condition->collectValidatedAttributes($productCollection);
            }
        }

        return $this;
    }

    /**
     * @param Collection $productCollection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addQtyFilter(Collection $productCollection)
    {
        $productCollection->joinField(
            'qty',
            'cataloginventory_stock_item',
            'qty',
            'product_id=entity_id',
            '{{table}}.stock_id=1',
            'left'
        );
    }

    /**
     * @param Collection $productCollection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addStockStatusFilter(Collection $productCollection)
    {
        $productCollection->joinField(
            'is_in_stock',
            'cataloginventory_stock_item',
            'is_in_stock',
            'product_id=entity_id',
            '{{table}}.stock_id=1',
            'left'
        );
    }
}

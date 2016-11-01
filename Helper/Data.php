<?php

namespace GoMage\Feed\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    protected $_attributeCollectionFactory;

    /**
     * @var \GoMage\Feed\Model\ResourceModel\Attribute\Collection
     */
    protected $_dynamicAttributeCollection;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory,
        \GoMage\Feed\Model\ResourceModel\Attribute\Collection $dynamicAttributeCollection
    ) {
        parent::__construct($context);
        $this->_attributeCollectionFactory = $attributeCollectionFactory;
        $this->_dynamicAttributeCollection = $dynamicAttributeCollection;
    }

    public function getProductAttributes()
    {
        $attributes = $this->_attributeCollectionFactory->create()->addVisibleFilter()->getItems();

        $attributes = array_map(function ($attribute) {
            return [
                'value' => $attribute->getAttributeCode(),
                'label' => $attribute->getStoreLabel()
            ];
        }, $attributes
        );

        //TODO: hard code
        $attributes[] = [
            'value' => 'id',
            'label' => __('Product Id')
        ];
        $attributes[] = [
            'value' => 'category_subcategory',
            'label' => __('Category > SubCategory')
        ];
        $attributes[] = [
            'value' => 'product_url',
            'label' => __('Product Url')
        ];

        usort($attributes, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        }
        );

        return $attributes;
    }

    /**
     * @return array
     */
    public function getDynamicAttributes()
    {
        $attributes = $this->_dynamicAttributeCollection->load()->getItems();

        $attributes = array_map(function (\GoMage\Feed\Model\Attribute $attribute) {
            return [
                'value' => $attribute->getCode(),
                'label' => $attribute->getName()
            ];
        }, $attributes
        );

        usort($attributes, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        }
        );

        return $attributes;
    }

}

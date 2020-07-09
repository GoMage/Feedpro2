<?php

namespace GoMage\Feed\Model\Rule\Condition;

use Magento\Catalog\Model\ProductCategoryList;

/**
 * Class Product
 * @package GoMage\Feed\Model\Rule\Condition
 */
class Product extends \Magento\CatalogRule\Model\Rule\Condition\Product
{
    /**
     * @var \Magento\Catalog\Model\Product\Type
     */
    protected $productType;

    /**
     * Product constructor.
     * @param \Magento\Rule\Model\Condition\Context $context
     * @param \Magento\Backend\Helper\Data $backendData
     * @param \Magento\Eav\Model\Config $config
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Catalog\Model\ResourceModel\Product $productResource
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection $attrSetCollection
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Catalog\Model\Product\Type $productType
     * @param array $data
     * @param ProductCategoryList|null $categoryList
     */
    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \Magento\Backend\Helper\Data $backendData,
        \Magento\Eav\Model\Config $config,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\ResourceModel\Product $productResource,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection $attrSetCollection,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Catalog\Model\Product\Type $productType,
        array $data = [],
        ProductCategoryList $categoryList = null
    ) {
        $this->productType = $productType;
        parent::__construct($context, $backendData, $config, $productFactory, $productRepository, $productResource, $attrSetCollection, $localeFormat, $data, $categoryList);
    }

    /**
     * @return string
     */
    public function getAttributeName()
    {
        if ($this->getAttribute() == 'type_id') {
            $name = __('Product Type');
        } elseif ($this->getAttribute() === 'qty') {
            $name = __('Quantity (per Default Stock)');
        } else {
            $name = $this->getAttributeOption($this->getAttribute());
        }

        return $name;
    }

    /**
     * @inheritdoc
     */
    protected function _prepareValueOptions()
    {
        if ($this->getAttribute() === 'type_id') {
            $selectOptions = $this->getAllProductTypesOptionsArray();
            $this->setData('value_select_options', $selectOptions);

            $hashedOptions = [];
            foreach ($selectOptions as $o) {
                if (is_array($o['value'])) {
                    continue; // We cannot use array as index
                }
                $hashedOptions[$o['value']] = $o['label'];
            }
            $this->setData('value_option', $hashedOptions);
        }
        parent::_prepareValueOptions();

        return $this;
    }

    /**
     * @return array
     */
    private function getAllProductTypesOptionsArray()
    {
        $productTypesOptionsArray = [];

        foreach ($this->productType->getTypes() as $value => $availableType) {
            $productTypesOptionsArray[] = [
                'value' => $value,
                'label' => $availableType['label']
            ];
        }

        return $productTypesOptionsArray;
    }

    /**
     * @inheritdoc
     */
    public function getValueElementType()
    {
        if ($this->getAttribute() === 'type_id') {
            return 'select';
        }

        return parent::getValueElementType();
    }

    /**
     * @return string
     */
    public function getInputType()
    {
        if ($this->getAttribute() === 'type_id') {
            return 'select';
        } elseif ($this->getAttribute() === 'qty') {
            return 'numeric';
        }

        return parent::getInputType();
    }
}

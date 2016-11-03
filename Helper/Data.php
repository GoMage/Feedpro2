<?php

namespace GoMage\Feed\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;

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

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadInterface
     */
    protected $_directory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory,
        \GoMage\Feed\Model\ResourceModel\Attribute\Collection $dynamicAttributeCollection,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->_attributeCollectionFactory = $attributeCollectionFactory;
        $this->_dynamicAttributeCollection = $dynamicAttributeCollection;
        $this->_directory                  = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $this->_storeManager               = $storeManager;
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

    /**
     * @param  string $fileName
     * @param  int $storeId
     * @return string
     */
    public function getAccessUrl($fileName = '', $storeId = 0)
    {
        if ($fileName && $storeId) {
            $path = \GoMage\Feed\Model\Writer\WriterInterface::DIRECTORY . '/' . $fileName;
            if ($this->_directory->isExist($path)) {
                return $this->_storeManager->getStore($storeId)->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . $path;
            }
        }
        return '';
    }

}

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

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_dateTime;


    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory,
        \GoMage\Feed\Model\ResourceModel\Attribute\Collection $dynamicAttributeCollection,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
    ) {
        parent::__construct($context);
        $this->_attributeCollectionFactory = $attributeCollectionFactory;
        $this->_dynamicAttributeCollection = $dynamicAttributeCollection;
        $this->_directory                  = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $this->_storeManager               = $storeManager;
        $this->_dateTime                   = $dateTime;
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
    public function getFeedUrl($fileName = '', $storeId = 0)
    {
        if ($fileName && $storeId) {
            $path = $this->getFeedPath($fileName);
            if ($path) {
                return $this->_storeManager->getStore($storeId)->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . $path;
            }
        }
        return '';
    }

    /**
     * @param  string $fileName
     * @return string
     */
    public function getFeedPath($fileName, $absolute = false)
    {
        $path = \GoMage\Feed\Model\Writer\WriterInterface::DIRECTORY . '/' . $fileName;
        if ($this->_directory->isExist($path)) {
            return $absolute ? $this->_directory->getAbsolutePath($path) : $path;
        }
        return '';
    }

    /**
     * @param int $interval
     * @param int $hourFrom
     * @param int $hourTo
     * @param string $lastRun
     * @return bool
     */
    public function needRunCron($interval, $hourFrom, $hourTo, $lastRun)
    {
        $current = $this->_dateTime->gmtDate('G');
        $lastRun = $this->_dateTime->gmtTimestamp($lastRun);

        switch ($interval) {
            case 12:
            case 24:
                if ($hourFrom != $current) {
                    return false;
                }
                if (($lastRun + $interval * 60 * 60) > $this->_dateTime->gmtTimestamp()) {
                    return false;
                }
                break;
            default:
                if (!$hourTo) {
                    $hourTo = 24;
                }

                $hours = array();
                if ($hourFrom > $hourTo) {
                    for ($i = $hourFrom; $i <= 23; $i++) {
                        $hours[] = $i;
                    }
                    for ($i = 0; $i <= $hourTo; $i++) {
                        $hours[] = $i;
                    }
                } else {
                    for ($i = $hourFrom; $i <= $hourTo; $i++) {
                        if ($i == 24) {
                            $hours[] = 0;
                        } else {
                            $hours[] = $i;
                        }
                    }
                }
                if (!in_array($current, $hours)) {
                    return false;
                }

                if (($lastRun + $interval * 60 * 60) > $this->_dateTime->gmtTimestamp()) {
                    return false;
                }
        }

        return true;
    }

}

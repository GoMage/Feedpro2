<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.1.1
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use GoMage\Core\Helper\Data as coreHelper;
class Data
{
    const MODULE_NAME = 'GoMage_Feed';

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
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadInterface
     */
    protected $_directory;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadInterface
     */
    protected $_customDirectory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_dateTime;

    /**
     * @var  \Magento\Framework\Module\ModuleListInterface
     */
    protected $_moduleList;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $_encryptor;

    /**
     * @var \GoMage\Feed\Model\Mapper\Factory
     */
    protected $_mapperFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var coreHelper
     */
    protected $_coreHelper;

    /**
     * @var \Magento\Config\Model\ConfigFactory
     */
    protected $_configFactory;

    /**
     * Data constructor.
     * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory
     * @param \GoMage\Feed\Model\ResourceModel\Attribute\Collection $dynamicAttributeCollection
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Store\Model\StoreManager $storeManager
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Framework\Module\ModuleList $moduleList
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Framework\Encryption\Encryptor $encryptor
     * @param \GoMage\Feed\Model\Mapper\Factory $mapperFactory
     * @param \Magento\Framework\App\Config $config
     * @param \Magento\Config\Model\ConfigFactory $configFactory
     * @param coreHelper $coreHelper
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory,
        \GoMage\Feed\Model\ResourceModel\Attribute\Collection $dynamicAttributeCollection,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Module\ModuleList $moduleList,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\Encryption\Encryptor $encryptor,
        \GoMage\Feed\Model\Mapper\Factory $mapperFactory,
        \Magento\Framework\App\Config $config,
        \Magento\Config\Model\ConfigFactory $configFactory,
        coreHelper $coreHelper
    ) {
        $this->_attributeCollectionFactory = $attributeCollectionFactory;
        $this->_dynamicAttributeCollection = $dynamicAttributeCollection;
        $this->_directory                  = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $this->_customDirectory            = $filesystem->getDirectoryRead(DirectoryList::PUB);
        $this->_storeManager               = $storeManager;
        $this->_systemStore                = $systemStore;
        $this->_dateTime                   = $dateTime;
        $this->_moduleList                 = $moduleList;
        $this->_jsonHelper                 = $jsonHelper;
        $this->_encryptor                  = $encryptor;
        $this->_mapperFactory              = $mapperFactory;
        $this->_scopeConfig                = $config;
        $this->_coreHelper                 = $coreHelper;
        $this->_configFactory              = $configFactory;
    }

    /**
     * @return array
     */
    public function getProductAttributes()
    {
        $attributes = $this->_attributeCollectionFactory->create()->addVisibleFilter()->getItems();
        $customMappers = $this->_mapperFactory->getCustomMappers();

        foreach ($attributes as $key => $attribute) {
            if (isset($customMappers[$attribute->getAttributeCode()])) {
                unset($attributes[$key]);
            }
        }

        $attributeList = [];
        foreach ($attributes as $attribute) {
            if ($attribute->getStoreLabel()) {
                $attributeList[] = [
                    'value' => $attribute->getAttributeCode(),
                    'label' => $attribute->getStoreLabel()
                ];
            }
        }

        foreach ($customMappers as $value => $class) {
            $attributeList[] = [
                'value' => $value,
                'label' => $class::getLabel()
            ];
        }

        usort($attributeList, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        }
        );

        return $attributeList;
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
     * @param string $fileName
     * @param int $storeId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getFeedUrl($fileName = '', $storeId = 0)
    {
        if ($fileName && $storeId) {
            $path = $this->getFeedPath($fileName);
            $customFolder = $this->getCustomFeedDirectory();
            if ($path) {
                if ($customFolder == '') {return $this->_storeManager->getStore($storeId)->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ) . $path;}
                else{
                    return $this->_storeManager->getStore($storeId)->getBaseUrl(
                            \Magento\Framework\UrlInterface::URL_TYPE_WEB
                        ) . $this->getDirectoryWright(). "/".$path;
                }
            }
        }
        return '';
    }

    /**
     * @param string $fileName
     * @param bool $absolute
     * @return string
     */
    public function getFeedPath($fileName, $absolute = false)
    {
        $feedDirectory = $this->getCustomFeedDirectory();
        if ($feedDirectory == '') {
            $path = \GoMage\Feed\Model\Writer\WriterInterface::DIRECTORY . '/' . $fileName;
            return $absolute ? $this->_directory->getAbsolutePath($path) : $path;
        }else{
            $path = $feedDirectory. '/' .\GoMage\Feed\Model\Writer\WriterInterface::DIRECTORY . '/' . $fileName;
            return $absolute ? $this->_customDirectory->getAbsolutePath($path) : $path;
        }
    }

    /**
     * @return string
     */
    public function getCustomFeedDirectory()
    {
        $feedDirectory = $this->_scopeConfig->getValue('gomage_feed/server/feed_folder');
        $feedDirectory = trim($feedDirectory, " \t\n\r\0\x0B/\\");
        return $feedDirectory;
    }

    /**
     * @return string
     */
    public function getDirectoryWright()
    {
        $feedDirectory = $this->getCustomFeedDirectory();
        return $feedDirectory == '' ? $directoryWright = \Magento\Framework\App\Filesystem\DirectoryList::MEDIA
            : $directoryWright = \Magento\Framework\App\Filesystem\DirectoryList::PUB;
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
        $lastRun = $lastRun ? $this->_dateTime->gmtTimestamp($lastRun) : 0;

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

                $hours = [];
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

    /**
     * @return string
     */
    private function _getVersion()
    {
        return $this->_moduleList->getOne(self::MODULE_NAME)['setup_version'];
    }

    /**
     * @return array
     */
    public function getStoreOptionArray()
    {
        $modulesAvailableStores =  $this->_coreHelper->getAvailableStores($this->_coreHelper->getN());
        $options = [];
        if(isset($modulesAvailableStores[self::MODULE_NAME])){
            foreach ( explode(',',$modulesAvailableStores[self::MODULE_NAME]) as $k => $id) {
                $options[$k]['value'] = $id;
                $options[$k]['label'] =  $this->_storeManager->getStore($id)->getName();
            }
        }
        return $options;
    }
}

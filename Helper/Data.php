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
     * @var \Magento\AdminNotification\Model\InboxFactory
     */
    protected $_inboxFactory;

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
     * @param \Magento\AdminNotification\Model\InboxFactory $inboxFactory
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
        \Magento\AdminNotification\Model\InboxFactory $inboxFactory,
        \Magento\Config\Model\ConfigFactory $configFactory,
        coreHelper $coreHelper
    ) {
        $this->_attributeCollectionFactory = $attributeCollectionFactory;
        $this->_dynamicAttributeCollection = $dynamicAttributeCollection;
        $this->_directory                  = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $this->_storeManager               = $storeManager;
        $this->_systemStore                = $systemStore;
        $this->_dateTime                   = $dateTime;
        $this->_moduleList                 = $moduleList;
        $this->_jsonHelper                 = $jsonHelper;
        $this->_encryptor                  = $encryptor;
        $this->_mapperFactory              = $mapperFactory;
        $this->_scopeConfig                = $config;
        $this->_coreHelper                 = $coreHelper;
        $this->_inboxFactory               = $inboxFactory;
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
     * @return bool
     */
    public function notify()
    {
        $frequency = (int)$this->_scopeConfig->getValue('gomage_notification/notification/frequency');
        if (!$frequency) {
            $frequency = 24;
        }
        $last_update = (int)$this->_scopeConfig->getValue('gomage_notification/notification/last_update');

        if (($frequency * 60 * 60 + $last_update) > $this->_dateTime->gmtTimestamp()) {
            return false;
        }

        $timestamp = $last_update;
        if (!$timestamp) {
            $timestamp = $this->_dateTime->gmtTimestamp();
        }

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, sprintf('https://www.gomage.com/index.php/gomage_notification/index/data'));
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'sku=feed-pro-m2&timestamp=' . $timestamp . '&ver=' . urlencode($this->_getVersion()));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            $content = curl_exec($ch);

            try {
                $result = $this->_jsonHelper->jsonDecode($content);
            } catch (\Exception $e) {
                $result = false;
            }

            if ($result && isset($result['frequency']) && ($result['frequency'] != $frequency)) {
                $frequency = $result['frequency'];
            }

            if ($result && isset($result['data'])) {
                if (!empty($result['data'])) {
                    /** @var \Magento\AdminNotification\Model\Inbox $inbox */
                    $inbox = $this->_inboxFactory->create();
                    $inbox->parse($result['data']);
                }
            }
        } catch (\Exception $e) {
        }

        $groups = [
            'notification' => [
                'fields' => [
                    'frequency'   => ['value' => $frequency],
                    'last_update' => ['value' => $this->_dateTime->gmtTimestamp()]
                ]
            ]
        ];

        /** @var \Magento\Config\Model\Config $config */
        $config = $this->_configFactory->create();

        $config->setSection('gomage_notification')
            ->setGroups($groups)
            ->save();

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

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
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;


    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory,
        \GoMage\Feed\Model\ResourceModel\Attribute\Collection $dynamicAttributeCollection,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        parent::__construct($context);
        $this->_attributeCollectionFactory = $attributeCollectionFactory;
        $this->_dynamicAttributeCollection = $dynamicAttributeCollection;
        $this->_directory                  = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $this->_storeManager               = $storeManager;
        $this->_systemStore                = $systemStore;
        $this->_dateTime                   = $dateTime;
        $this->_moduleList                 = $moduleList;
        $this->_jsonHelper                 = $jsonHelper;
        $this->_encryptor                  = $encryptor;
        $this->_objectManager              = $objectManager;
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

    /**
     * @return array
     */
    public function getAvailableWebsites()
    {
        if (!$this->scopeConfig->getValue('gomage_activation/feed/installed') ||
            (intval($this->scopeConfig->getValue('gomage_activation/feed/count')) > 10)
        ) {
            return [];
        }

        $time_to_update = 60 * 60 * 24 * 15;

        $r = $this->scopeConfig->getValue('gomage_activation/feed/ar');
        $t = $this->scopeConfig->getValue('gomage_activation/feed/time');
        $s = $this->scopeConfig->getValue('gomage_activation/feed/websites');

        $last_check = str_replace($r, '', $this->_encryptor->decrypt($t));

        $sites = explode(',', str_replace($r, '', $this->_encryptor->decrypt($s)));
        $sites = array_diff($sites, array(""));

        if (($last_check + $time_to_update) < $this->_dateTime->gmtTimestamp()) {
            $this->a(intval($this->scopeConfig->getValue('gomage_activation/feed/count')),
                implode(',', $sites)
            );
        }

        return $sites;
    }

    public function a($c = 0, $s = '')
    {
        $k = $this->scopeConfig->getValue('gomage_settings/feed/key');

        /** @var \Magento\Config\Model\Config $config */
        $config = $this->_objectManager->create('Magento\Config\Model\Config');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf('https://www.gomage.com/index.php/gomage_downloadable/key/check'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'key=' . urlencode($k) . '&sku=feed-pro&domains=' . urlencode(implode(',', $this->_getDomains())) . '&ver=' . urlencode($this->_getVersion()));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $content = curl_exec($ch);
        try {
            $r = $this->_jsonHelper->jsonDecode($content);
        } catch (\Exception $e) {
            $r = [];
        }

        if (empty($r)) {

            $value1 = $this->scopeConfig->getValue('gomage_activation/feed/ar');

            $groups = array(
                'feed' => array(
                    'fields' => array(
                        'ar'       => array(
                            'value' => $value1
                        ),
                        'websites' => array(
                            'value' => (string)$this->scopeConfig->getValue('gomage_activation/feed/websites')
                        ),
                        'time'     => array(
                            'value' => (string)$this->_encryptor->encrypt($value1 . ($this->_dateTime->gmtTimestamp() - (60 * 60 * 24 * 15 - 1800)) . $value1)
                        ),
                        'count'    => array(
                            'value' => $c + 1)
                    )
                )
            );

            $config->setSection('gomage_activation')
                ->setGroups($groups)
                ->save();
            return;
        }

        $value1 = '';
        $value2 = '';

        if (isset($r['d']) && isset($r['c'])) {
            $value1 = $this->_encryptor->encrypt(base64_encode($this->_jsonHelper->jsonEncode($r)));

            if (!$s) {
                $s = $this->scopeConfig->getValue('gomage_settings/feed/websites');
            }

            $s = array_slice(explode(',', $s), 0, $r['c']);

            $value2 = $this->_encryptor->encrypt($value1 . implode(',', $s) . $value1);

        }
        $groups = array(
            'feed' => array(
                'fields' => array(
                    'ar'        => array(
                        'value' => $value1
                    ),
                    'websites'  => array(
                        'value' => (string)$value2
                    ),
                    'time'      => array(
                        'value' => (string)$this->_encryptor->encrypt($value1 . $this->_dateTime->gmtTimestamp() . $value1)
                    ),
                    'installed' => array(
                        'value' => 1
                    ),
                    'count'     => array(
                        'value' => 0)

                )
            )
        );

        $config->setSection('gomage_activation')
            ->setGroups($groups)
            ->save();
    }

    /**
     * @return mixed
     */
    public function ga()
    {
        $value = base64_decode($this->_encryptor->decrypt($this->scopeConfig->getValue('gomage_activation/feed/ar')));
        if ($value) {
            return $this->_jsonHelper->jsonDecode($value);
        }
        return [];
    }

    /**
     * @return array
     */
    private function _getDomains()
    {
        $domains = [];

        /** @var \Magento\Store\Model\Website $website */
        foreach ($this->_storeManager->getWebsites() as $website) {

            $url = $website->getConfig('web/unsecure/base_url');

            if ($domain = trim(preg_replace('/^.*?\\/\\/(.*)?\\//', '$1', $url))) {
                $domains[] = $domain;
            }

            $url = $website->getConfig('web/secure/base_url');

            if ($domain = trim(preg_replace('/^.*?\\/\\/(.*)?\\//', '$1', $url))) {
                $domains[] = $domain;
            }
        }
        return array_unique($domains);
    }

    /**
     * @return string
     */
    private function _getVersion()
    {
        return $this->_moduleList->getOne($this->_getModuleName())['setup_version'];
    }

    public function getStoreOptionArray()
    {
        $options   = [];
        $options[] = ['label' => '', 'value' => ''];

        $websites = $this->getAvailableWebsites();

        $nonEscapableNbspChar = html_entity_decode('&#160;', ENT_NOQUOTES, 'UTF-8');

        foreach ($this->_systemStore->getWebsiteCollection() as $website) {

            if (!in_array($website->getId(), $websites)) {
                continue;
            }

            $websiteShow = false;
            foreach ($this->_systemStore->getGroupCollection() as $group) {
                if ($website->getId() != $group->getWebsiteId()) {
                    continue;
                }
                $groupShow = false;
                foreach ($this->_systemStore->getStoreCollection() as $store) {
                    if ($group->getId() != $store->getGroupId()) {
                        continue;
                    }
                    if (!$websiteShow) {
                        $options[]   = ['label' => $website->getName(), 'value' => []];
                        $websiteShow = true;
                    }
                    if (!$groupShow) {
                        $groupShow = true;
                        $values    = [];
                    }
                    $values[] = [
                        'label' => str_repeat($nonEscapableNbspChar, 4) . $store->getName(),
                        'value' => $store->getId(),
                    ];
                }
                if ($groupShow) {
                    $options[] = [
                        'label' => str_repeat($nonEscapableNbspChar, 4) . $group->getName(),
                        'value' => $values,
                    ];
                }
            }
        }
        return $options;
    }

}

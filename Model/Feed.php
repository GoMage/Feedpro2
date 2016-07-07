<?php
// @codingStandardsIgnoreFile

namespace GoMage\Feed\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use GoMage\Feed\Model\Config\Source\Enclosure;
use GoMage\Feed\Model\Config\Source\Delimiter;
use GoMage\Feed\Model\Config\Source\Mapping\Output;

class Feed extends \Magento\Framework\Model\AbstractModel
{

    const CSV_TYPE = 'csv';
    const XML_TYPE = 'xml';

    const DIRECTORY = 'gomage_feed';

    /**
     * Writer object instance.
     *
     * @var \GoMage\Feed\Model\Adapter\AbstractAdapter
     */
    protected $_writer;

    /**
     * @var \GoMage\Feed\Model\Adapter\Factory
     */
    protected $_adapterFactory;

    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;

    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepository;

    /**
     * @var ProductAttributeRepositoryInterface
     */
    protected $_attributeRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $_categoryRepository;

    /**
     * @var array
     */
    protected $_fieldsMapping;

    /**
     * @var Enclosure
     */
    protected $_enclosureModel;

    /**
     * @var Delimiter
     */
    protected $_delimiterModel;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $_directoryList;


    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \GoMage\Feed\Model\Adapter\Factory $adapterFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        ProductRepositoryInterface $productRepository,
        ProductAttributeRepositoryInterface $attributeRepository,
        CategoryRepositoryInterface $categoryRepository,
        Enclosure $enclosure,
        Delimiter $delimiter,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Filesystem\DirectoryList $_directoryList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_adapterFactory           = $adapterFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_productRepository        = $productRepository;
        $this->_attributeRepository      = $attributeRepository;
        $this->_categoryRepository       = $categoryRepository;

        $this->_enclosureModel = $enclosure;
        $this->_delimiterModel = $delimiter;

        $this->_storeManager = $storeManager;

        $this->_directoryList = $_directoryList;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Init model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('GoMage\Feed\Model\Resource\Feed');
    }

    /**
     * Get writer object.
     *
     * @return \GoMage\Feed\Model\Adapter\AbstractAdapter
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getWriter()
    {
        if (!$this->_writer) {
            try {
                //TODO: choose type of feed (csv, xml)
                $class         = 'GoMage\Feed\Model\Adapter\Csv';
                $this->_writer = $this->_adapterFactory->create($class, ['destination' => $this->getDestination()]);
                $this->_writer->setDelimiter($this->_delimiterModel->getSymbol($this->getDelimiter()));
                $this->_writer->setEnclosure($this->_enclosureModel->getSymbol($this->getEnclosure()));

            } catch (\Exception $e) {
                $this->_logger->critical($e);
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Please enter a correct entity model.')
                );
            }
            if (!$this->_writer instanceof \GoMage\Feed\Model\Adapter\AbstractAdapter) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __(
                        'The adapter object must be an instance of %1.',
                        '\GoMage\Feed\Model\Adapter\AbstractAdapter'
                    )
                );
            }
        }
        return $this->_writer;
    }

    protected function log($message)
    {
        $file   = $this->_directoryList->getPath('log') . '/feed-' . $this->getId() . '.log';
        $string = date("m.d.y H:i:s") . ' ' . $message . PHP_EOL;
        file_put_contents($file, $string, FILE_APPEND);
    }

    public function generate()
    {
        $this->log('START');
        $this->log('Page Size:' . $this->getLimit());

        $collection = $this->_getProductCollection();
        $this->log('Items:' . $collection->count());

        //Execution time may be very long
        set_time_limit(0);

        $writer = $this->_getWriter();
        $page   = 0;
        while (true) {
            ++$page;

            $this->log('Page - ' . $page);

            $collection = $this->_getProductCollection();
            $collection->setPage($page, $this->getLimit());

            if ($collection->count() == 0) {
                break;
            }

            $generationData = $this->getGenerationData($collection);
            if ($page == 1) {
                $writer->setHeaderCols($this->_getHeaderColumns());
            }
            foreach ($generationData as $dataRow) {
                $writer->writeRow($dataRow);
            }
            if ($collection->getCurPage() >= $collection->getLastPageNumber()) {
                break;
            }
        }
        $this->log('END');
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected function _getProductCollection()
    {
        $collection = $this->_productCollectionFactory->create();

        $collection->setStoreId($this->getStoreId())
            ->setVisibility($this->_catalogProductVisibility->getVisibleInSiteIds());

        $ids = [];
        if ($filter = $this->getFilter()) {
            $filter = json_decode($filter, true);
            foreach ($filter as $row) {
                $ids[] = intval($row['value']);
            }
        }

        if (count($ids)) {
            $collection->addAttributeToFilter('manufacturer_for_navigation', ['in' => $ids]);
        }

        $collection->getSelect()->distinct();
        $collection->setOrder('id');

        return $collection;
    }

    /**
     * @return array
     */
    protected function _getHeaderColumns()
    {
        return [];
    }

    /**
     * @param  \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return array
     */
    protected function getGenerationData($collection)
    {
        $data = [];
        foreach ($collection as $product) {
            $data[$product->getId()] = $this->getProductData($product->getId());
        }
        return $data;
    }

    /**
     * @param  integer $productId
     * @return array
     */
    protected function getProductData($productId)
    {
        $product = $this->_productRepository->getById($productId, false, $this->getStoreId(), true);

        $data          = [];
        $fieldsMapping = $this->getFieldsMapping();

        foreach ($fieldsMapping as $field) {
            $value = $this->getFieldData($product, $field->prefix_type, $field->prefix_value) .
                $this->getFieldData($product, $field->type, $field->value) .
                $this->getFieldData($product, $field->suffix_type, $field->suffix_value);

            if ($limit = intval($field->limit)) {
                $value = $value = substr($value, 0, $limit);
            }

            if ($output = intval($field->output)) {
                switch (trim($output)) {
                    case Output::FLOAT:
                        $value = number_format(( float )$value, 2, '.', '');
                        break;
                    case Output::INTEGER:
                        $value = intval($value);
                        break;
                    case Output::STRIP_TAGS:
                        $value = strip_tags($value);
                        $value = trim($value);
                        break;
                    case Output::SPECIAL_ENCODE:
                        $encoding = mb_detect_encoding($value);
                        $value    = mb_convert_encoding($value, "UTF-8", $encoding);
                        $value    = htmlentities($value, ENT_QUOTES, "UTF-8");
                        break;
                    case Output::SPECIAL_DECODE:
                        $value = htmlspecialchars_decode($value);
                        break;
                    case Output::DELETE_SPACE:
                        $value = str_replace(" ", "", $value);
                        break;
                    case Output::BIG_TO_SMALL:
                        $value = strtolower($value);
                        break;
                }
            }

            //TODO: hard code only for icemaker
            $value = str_replace('&amp;amp;', '&amp;', $value);

            $data[$field->name] = $value;
        }

        return $data;
    }

    /**
     * @param  \Magento\Catalog\Model\Product $product
     * @param  string $type
     * @param  string $code
     * @return mixed
     */
    protected function getFieldData($product, $type, $code)
    {
        if ($type == \GoMage\Feed\Model\Config\Source\Mapping\TypeInterface::STATIC_VALUE) {
            return $code;
        }

        if (!$code) {
            return "";
        }

        //TODO: hard code
        if ($code == 'id') {
            return $product->getId();
        } elseif ($code == 'category_subcategory') {
            $categoryIds = $product->getCategoryIds();
            if (count($categoryIds)) {
                $categoryId = max($categoryIds);
                $category   = $this->_categoryRepository->get($categoryId);
                $categories = $category->getParentCategories();
                return implode(' > ', array_map(function ($cat) {
                        return $cat->getName();
                    }, $categories
                    )
                );
            }
            return "";
        } elseif ($code == 'free_shipping_feed') {
            return ($product->getWeight() ? '' : 'US:::0.00 USD');
        } elseif ($code == 'weight') {
            return $product->getData('free_shipping') ? 0 : $product->getWeight();
        } elseif ($code == 'url_key') {
            return $product->getProductUrl();
        } elseif ($code == 'small_image') {
            return $this->_getMediaUrl($product->getImage());
        } elseif ($code == 'price') {
            return $product->getPrice() ? $product->getPrice() : $product->getFinalPrice();
        }

        $attribute = $this->getProductAttribute($code);
        $value     = $attribute->getFrontend()->getValue($product);

        if ($this->getIsRemoveLb()) {
            $value = str_replace("\n", '', $value);
            $value = str_replace("\r", '', $value);
        }

        //TODO: hard code
        if (in_array($value, ['Not Specified', 'No', 'Use config'])) {
            $value = '';
        }

        return $value;
    }

    /**
     * Get media url
     *
     * @param string $url
     * @return string
     */
    protected function _getMediaUrl($url)
    {
        //TODO: hard code
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $url;
    }

    /**
     * @param $code
     * @return \Magento\Catalog\Api\Data\ProductAttributeInterface
     */
    protected function getProductAttribute($code)
    {
        return $this->_attributeRepository->get($code);
    }

    /**
     * @return array
     */
    protected function getFieldsMapping()
    {
        if (is_null($this->_fieldsMapping)) {
            $this->_fieldsMapping = json_decode($this->getContent());
        }
        return $this->_fieldsMapping;
    }


    /**
     * @return string
     */
    public function getDestination()
    {
        return self::DIRECTORY . '/' . $this->getFilename() . '.' . $this->getFileExt();
    }

}
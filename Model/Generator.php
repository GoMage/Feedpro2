<?php
// @codingStandardsIgnoreFile

namespace GoMage\Feed\Model;

use Magento\Framework\App\Filesystem\DirectoryList;

class Generator implements GeneratorInterface
{

    /**
     * @var Feed
     */
    protected $_feed;

    /**
     * @var \Magento\Framework\Filesystem\Directory\Write
     */
    protected $_directory;

    /**
     * @var \Magento\Framework\Filesystem\File\Write
     */
    protected $_stream;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $_escaper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_dateModel;

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
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Framework\Filesystem $filesystem
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateModel,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        array $data = []
    ) {

        $this->_escaper   = $escaper;
        $this->_directory = $filesystem->getDirectoryWrite(DirectoryList::ROOT);
        $this->_dateModel = $dateModel;

        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;


        parent::__construct($data);
    }

    /**
     * Get file handler
     *
     * @return \Magento\Framework\Filesystem\File\WriteInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getStream()
    {
        if ($this->_stream) {
            return $this->_stream;
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(__('File handler unreachable'));
        }
    }

    /**
     * @param Feed $feed
     * @return bool
     */
    public function generate(Feed $feed)
    {
        $this->_feed = $feed;

        $this->_feed->setData('generated_at', $this->_dateModel->date('Y-m-d H:i:s'))
            ->save();

        return true;
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected function getProductCollection()
    {
        $collection = $this->_productCollectionFactory->create();

        $collection->setStoreId($this->_feed->getStoreId())
            ->setVisibility($this->_catalogProductVisibility->getVisibleInSiteIds());

        if ($filter = $this->_feed->getFilter()) {
            $filter = json_decode($filter, true);
        }

        return $collection;
    }

}

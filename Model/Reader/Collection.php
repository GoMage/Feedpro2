<?php

namespace GoMage\Feed\Model\Reader;

class Collection implements ReaderInterface
{

    const SORT_ATTRIBUTE = 'entity_id';

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $_collection;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;

    /**
     * @var array
     */
    protected $_attributes;

    /**
     * @var array
     */
    protected $_filters;

    /**
     * @var int
     */
    protected $_storeId;


    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        $attributes = [],
        $filters = [],
        $storeId = 0
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_attributes               = $attributes;
        $this->_filters                  = $filters;
        $this->_storeId                  = $storeId;
    }


    /**
     * @param  int $page
     * @param  int $limit
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function read($page, $limit)
    {
        $collection = $this->_getCollection();
        $collection->setPage($page, $limit);
        if (!$collection->count()) {
            return false;
        }
        if ($page > $collection->getLastPageNumber()) {
            return false;
        }
        return $collection;
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected function _getCollection()
    {
        if (is_null($this->_collection)) {
            $this->_collection = $this->_productCollectionFactory->create();

            if ($this->_storeId) {
                $this->_collection->setStoreId($this->_storeId);
            }

            // TODO: hard code
            $this->_collection->setVisibility($this->_catalogProductVisibility->getVisibleInSiteIds());
            $ids = [];
            foreach ($this->_filters as $filter) {
                $ids[] = intval($filter['value']);
            }
            if (count($ids)) {
                $this->_collection->addAttributeToFilter('brand', ['in' => $ids]);
            }
            //TODO: end hard code

            $this->_collection->addFieldToSelect($this->_attributes)
                ->addAttributeToSort(self::SORT_ATTRIBUTE);
        }
        return $this->_collection->clear();
    }


}
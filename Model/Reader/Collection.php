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
     * @var \Magento\Rule\Model\Condition\Combine
     */
    protected $_conditions;

    /**
     * @var \Magento\Rule\Model\Condition\Sql\Builder
     */
    protected $_builder;

    /**
     * @var int
     */
    protected $_storeId;


    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        $attributes = [],
        \Magento\Rule\Model\Condition\Combine $conditions,
        \Magento\Rule\Model\Condition\Sql\Builder $builder,
        $storeId = 0
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_attributes               = $attributes;
        $this->_conditions               = $conditions;
        $this->_builder                  = $builder;
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

            //TODO: add Visibility param
            $this->_collection->setVisibility($this->_catalogProductVisibility->getVisibleInSiteIds());

            $this->_conditions->collectValidatedAttributes($this->_collection);
            $this->_builder->attachConditionToCollection($this->_collection, $this->_conditions);

            $this->_collection->addFieldToSelect($this->_attributes)
                ->addAttributeToSort(self::SORT_ATTRIBUTE);
        }
        return $this->_collection->clear();
    }

}
<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.1.1
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Reader;

class Collection implements ReaderInterface
{

    const SORT_ATTRIBUTE = 'entity_id';

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $_collection;

    /**
     * @var \GoMage\Feed\Model\Reader\Params
     */
    protected $_params;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var \GoMage\Feed\Model\Config\Source\Visibility
     */
    protected $_visibility;

    /**
     * @var \Magento\Rule\Model\Condition\Sql\Builder
     */
    protected $_builder;

    /**
     * @var \Magento\CatalogInventory\Helper\Stock
     */
    protected $_stockFilter;


    public function __construct(
        \GoMage\Feed\Model\Reader\Params $params,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \GoMage\Feed\Model\Config\Source\Visibility $visibility,
        \Magento\Rule\Model\Condition\Sql\Builder $builder,
        \Magento\CatalogInventory\Helper\Stock $stockFilter
    ) {
        $this->_params                   = $params;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_visibility               = $visibility;
        $this->_builder                  = $builder;
        $this->_stockFilter              = $stockFilter;
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
        if (!$collection->getSize()) {
            return false;
        }
        if ($page > $collection->getLastPageNumber()) {
            return false;
        }
        return $collection;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        $collection = $this->_getCollection();

        return $collection->getSize();
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected function _getCollection()
    {
        if (is_null($this->_collection)) {
            $this->_collection = $this->_productCollectionFactory->create();

            if ($this->_params->getStoreId()) {
                $this->_collection->addStoreFilter($this->_params->getStoreId());
            }

            $visibility = $this->_visibility->getProductVisibility($this->_params->getVisibility());
            if (is_array($visibility) && !empty($visibility)) {
                $this->_collection->addAttributeToFilter('visibility', ['in' => $visibility]);
            }

            if (!$this->_params->getIsDisabled()) {
                $this->_collection->addAttributeToFilter('status', ['eq' => 1]);
            }

            if (!$this->_params->getIsOutOfStock()) {
                $this->_stockFilter->addInStockFilterToCollection($this->_collection);
            }

            $this->_params->getConditions()->collectValidatedAttributes($this->_collection);
            $this->_builder->attachConditionToCollection($this->_collection, $this->_params->getConditions());

            $this->_collection->addFieldToSelect($this->_params->getAttributes())
                ->addAttributeToSort(self::SORT_ATTRIBUTE);
        }
        return $this->_collection->clear();
    }

}

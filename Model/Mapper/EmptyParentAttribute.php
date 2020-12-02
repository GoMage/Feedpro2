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
 * @version      Release: 1.3.2
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Mapper;

use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Psr\Log\LoggerInterface;

class EmptyParentAttribute extends Attribute implements MapperInterface
{
    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * EmptyParentAttribute constructor.
     * @param $value
     * @param ProductAttributeRepositoryInterface $attributeRepository
     * @param ResourceConnection $resource
     * @param LoggerInterface $logger
     * @param CollectionFactory $productCollectionFactory
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function __construct(
        $value,
        ProductAttributeRepositoryInterface $attributeRepository,
        ResourceConnection $resource,
        LoggerInterface $logger,
        CollectionFactory $productCollectionFactory
    ) {
        parent::__construct($value, $attributeRepository, $resource, $logger);
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @param  DataObject $object
     * @return mixed
     */
    public function map(DataObject $object)
    {
        $result = parent::map($object);
        if (!empty($result)) {
            return $result;
        }
        $childProduct = $this->_getChildProduct($object);
        if ($childProduct) {
            return parent::map($childProduct);
        }
        return '';
    }

    /**
     * @param DataObject $object
     * @return bool|DataObject
     */
    protected function _getChildProduct(DataObject $object)
    {
        $childId = $this->resource->getConnection()
            ->select()
            ->from($this->resource->getTableName('catalog_product_relation'), 'child_id')
            ->where('parent_id = ?', $object->getId())
            ->where('child_id != ?', $object->getId())
            ->query()
            ->fetchColumn();

        if ($childId) {
            $collection = $this->productCollectionFactory->create();
            return $collection->addAttributeToSelect($this->_code)
                ->addIdFilter($childId)
                ->fetchItem();
        }
        return false;
    }
}

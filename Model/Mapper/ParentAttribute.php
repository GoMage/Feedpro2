<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2021 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.4.1
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Mapper;

use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Psr\Log\LoggerInterface;

class ParentAttribute extends Attribute implements MapperInterface
{
    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * ParentAttribute constructor.
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
        $parentProduct = $this->_getParentProduct($object);
        if ($parentProduct) {
            return parent::map($parentProduct);
        }
        return '';
    }

    /**
     * @param DataObject $object
     * @return bool|DataObject
     */
    protected function _getParentProduct(DataObject $object)
    {
        $parentId = $this->resource->getConnection()
            ->select()
            ->from($this->resource->getTableName('catalog_product_relation'), 'parent_id')
            ->where('child_id = ?', $object->getId())
            ->where('parent_id != ?', $object->getId())
            ->query()
            ->fetchColumn();
        if ($parentId) {
            $collection = $this->productCollectionFactory->create();
            return $collection->addAttributeToSelect($this->_code)
                ->addIdFilter($parentId)
                ->getFirstItem();
        }
        return false;
    }
}

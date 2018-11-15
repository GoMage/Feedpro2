<?php

/**
 * GoMage.com
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.2.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Mapper;

class ParentDynamicAttribute extends DynamicAttribute implements MapperInterface
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resource;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    private $connection;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @param $value
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    public function __construct(
        $value,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
        $this->resource = $resource;
        $this->connection = $resource->getConnection();
        $this->productCollectionFactory = $productCollectionFactory;

        parent::__construct($value, $jsonHelper, $objectManager);
    }

    /**
     * @param  \Magento\Framework\DataObject $object
     *
     * @return mixed
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        $parentProduct = $this->getParentProduct($object);
        if ($parentProduct) {
            return parent::map($parentProduct);
        }

        return '';
    }

    /**
     * @param \Magento\Framework\DataObject $object
     *
     * @return bool|\Magento\Framework\DataObject
     */
    private function getParentProduct(\Magento\Framework\DataObject $object)
    {
        $parentId = $this->connection
            ->select()
            ->from($this->resource->getTableName('catalog_product_relation'), 'parent_id')
            ->where('child_id = ?', $object->getId())
            ->where('parent_id != ?', $object->getId())
            ->query()
            ->fetchColumn();

        if ($parentId) {
            $collection = $this->productCollectionFactory->create();

            return $collection->addAttributeToSelect($this->getUsedAttributes())
                ->addIdFilter($parentId)
                ->getFirstItem();
        }

        return false;
    }
}

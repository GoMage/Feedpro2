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
 * @version      Release: 1.3.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Mapper;

use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
use Psr\Log\LoggerInterface;

class Attribute implements MapperInterface
{
    /**
     * @var string
     */
    protected $_code;

    /**
     * @var \Magento\Catalog\Api\Data\ProductAttributeInterface
     */
    protected $_attribute;

    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Attribute constructor.
     * @param $value
     * @param ProductAttributeRepositoryInterface $attributeRepository
     * @param ResourceConnection $resource
     * @param LoggerInterface $logger
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function __construct(
        $value,
        ProductAttributeRepositoryInterface $attributeRepository,
        ResourceConnection $resource,
        LoggerInterface $logger
    ) {
        $this->_code      = $value;
        $this->_attribute = $attributeRepository->get($this->_code);
        $this->resource = $resource;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return mixed|string
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        if ($this->_code == 'quantity_and_stock_status') {
            try {
                $productId = $object->getId();
                $tableName = $this->resource->getTableName('cataloginventory_stock_item');
                $connection = $this->resource->getConnection();
                $select = $connection->select()->from($tableName, 'qty')->where('product_id = ?', $productId);
                $value = $connection->fetchOne($select);
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
                $value = '';
            }

            return $value;
        }
        return $this->_attribute->getFrontendModel() == 'Magento\Catalog\Model\Product\Attribute\Frontend\Image' ?
            $this->_attribute->getFrontend()->getUrl($object) :
            $this->_attribute->getFrontend()->getValue($object);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return [$this->_code];
    }
}

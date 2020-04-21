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
 * @version      Release: 1.2.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Mapper;

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
     * @var \Magento\CatalogInventory\Api\StockItemRepositoryInterface
     */
    private $stockItemRepository;

    /**
     * Attribute constructor.
     * @param $value
     * @param \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository
     * @param \Magento\CatalogInventory\Api\StockItemRepositoryInterface $stockItemRepository
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function __construct(
        $value,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository,
        \Magento\CatalogInventory\Api\StockItemRepositoryInterface $stockItemRepository
    ) {
        $this->_code      = $value;
        $this->_attribute = $attributeRepository->get($this->_code);
        $this->stockItemRepository = $stockItemRepository;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        if ($this->_code == 'quantity_and_stock_status') {
            $stockItem = $this->stockItemRepository->get($object->getId());
            $value = $stockItem->getIsInStock() ? 'In Stock' : 'Out of Stock';
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

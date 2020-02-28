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

class AttributePercent extends Attribute implements MapperInterface
{
    /**
     * @var float
     */
    protected $_percent;

    public function __construct(
        $value,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository,
        \Magento\CatalogInventory\Api\StockItemRepositoryInterface $stockItemRepository
    ) {
        $this->_percent = floatval($value['percent']);
        parent::__construct($value['code'], $attributeRepository, $stockItemRepository);
    }

    /**
     * @param  \Magento\Framework\DataObject $object
     * @return float
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        return floatval(parent::map($object)) * $this->_percent / 100;
    }
}

<?php

namespace GoMage\Feed\Model\Mapper\Custom;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Magento\Framework\ObjectManagerInterface;

class msiStock extends Attribute
{
    protected $objectManager;

    public function __construct(
        $value,
        $type,
        ResourceConnection $resource,
        CollectionFactory $productCollectionFactory,
        ObjectManagerInterface $objectManager
    ) {
        parent::__construct($value, $type, $resource, $productCollectionFactory);
        $this->objectManager = $objectManager;
    }

    public function map(DataObject $object)
    {
        $quantity = 0;
        if ($object->getTypeId() == \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
            $quantity = $this->objectManager->get('\Magento\InventorySalesApi\Api\GetProductSalableQtyInterface')
                    ->execute($object->getSku(), $this->value);
        }

        return $quantity;
    }

    public static function getLabel()
    {
        return __('MSI Stock');
    }

    public function getUsedAttributes()
    {
        return ['msiStock'];
    }
}

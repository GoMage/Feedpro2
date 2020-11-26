<?php

namespace GoMage\Feed\Model\Mapper\Custom;

use Magento\Framework\DataObject;
use Magento\Framework\ObjectManagerInterface;

class msiStock implements \GoMage\Feed\Model\Mapper\Custom\CustomMapperInterface
{
    protected $objectManager;

    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
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
        return __('');
    }

    public function getUsedAttributes()
    {
        return ['msiStock'];
    }
}

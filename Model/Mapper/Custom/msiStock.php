<?php

namespace GoMage\Feed\Model\Mapper\Custom;

use Magento\Framework\DataObject;
use Magento\Framework\ObjectManagerInterface;

class msiStock implements \GoMage\Feed\Model\Mapper\Custom\CustomMapperInterface
{
    protected $objectManager;

    private $value;

    /**
     * @var \Magento\InventorySalesApi\Api\GetProductSalableQtyInterface
     */
    private $getProductSalableQty;

    public function __construct(
        $value,
        ObjectManagerInterface $objectManager,
        \Magento\InventorySalesApi\Api\GetProductSalableQtyInterface $getProductSalableQty
    ) {
        $this->value = $value;
        $this->objectManager = $objectManager;
        $this->getProductSalableQty = $getProductSalableQty;
    }

    public function map(DataObject $object)
    {
        if (is_string($this->value)) {
            $ar = explode(',', $this->value);
            if (!empty($ar) && $ar[0] === 'msiStock') {
                $id = $ar[1];
            }
        }
        $quantity = 0;
        if ($object->getTypeId() == \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
            $quantity = $this->getProductSalableQty->execute($object->getSku(), $id);
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

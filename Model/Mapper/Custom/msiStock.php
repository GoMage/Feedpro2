<?php
declare(strict_types=1);
namespace GoMage\Feed\Model\Mapper\Custom;

use Magento\Framework\DataObject;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;

class msiStock implements CustomMapperInterface
{
    /**
     * @var
     */
    protected $value;

    /**
     * @var \Magento\InventorySalesApi\Api\GetProductSalableQtyInterface
     */
    protected $getProductSalableQty;

    /**
     * msiStock constructor.
     * @param $value
     * @param GetProductSalableQtyInterface $getProductSalableQty
     */
    public function __construct(
        $value,
        GetProductSalableQtyInterface $getProductSalableQty
    ) {
        $this->value = $value;
        $this->getProductSalableQty = $getProductSalableQty;
    }

    /**
     * @param DataObject $object
     * @return float|int
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function map(DataObject $object)
    {
        $quantity = 0;
        if (is_string($this->value)) {
            $msiValueArray = explode(',', $this->value);
            if (!empty($msiValueArray) && $msiValueArray[0] === 'msiStock') {
                $stockId = (int)$msiValueArray[1];
                if ($object->getTypeId() == \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
                    $quantity = $this->getProductSalableQty->execute($object->getSku(), $stockId);
                }
            }
        }
        return $quantity;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public static function getLabel()
    {
        return __('');
    }

    /**
     * @return string[]
     */
    public function getUsedAttributes()
    {
        return ['msiStock'];
    }
}

<?php
declare(strict_types=1);
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
 * @since        Class available since Release 1.3.0
 */

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

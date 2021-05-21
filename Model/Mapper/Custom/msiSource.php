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
use Magento\InventoryApi\Api\GetSourceItemsBySkuInterface;

class msiSource implements CustomMapperInterface
{
    /**
     * @var
     */
    protected $value;

    /**
     * @var GetSourceItemsBySkuInterface
     */
    protected $getSourceItemsBySku;

    /**
     * msiSource constructor.
     * @param $value
     * @param GetSourceItemsBySkuInterface $getSourceItemsBySku
     */
    public function __construct(
        $value,
        GetSourceItemsBySkuInterface $getSourceItemsBySku
    ) {
        $this->value = $value;
        $this->getSourceItemsBySku = $getSourceItemsBySku;
    }

    /**
     * @param DataObject $object
     * @return float|int
     */
    public function map(DataObject $object)
    {
        $quantity = 0;
        if (is_string($this->value)) {
            $msiValueArray = explode(',', $this->value);
            if (!empty($msiValueArray) && $msiValueArray[0] === 'msiSource') {
                $sourceCode = $msiValueArray[1];
                $sourceItemsBySku = $this->getSourceItemsBySku->execute($object->getSku());

                foreach ($sourceItemsBySku as $sourceItem) {
                    if ($sourceItem->getData('source_code') == $sourceCode) {
                        $quantity = $sourceItem->getQuantity();
                        return $quantity;
                    }
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
        return ['msiSource'];
    }
}

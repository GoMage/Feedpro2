<?php
declare(strict_types=1);
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
            $ar = explode(',', $this->value);
            if (!empty($ar) && $ar[0]==='msiSource') {
                $sourceId= $ar[1];
            }
        }
        $sourceItemsBySku = $this->getSourceItemsBySku->execute($object->getSku());

        foreach ($sourceItemsBySku as $sourceItem) {
            if ($sourceItem->getData('source_code') == (int)$sourceId) {
                $quantity = $sourceItem->getQuantity();
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

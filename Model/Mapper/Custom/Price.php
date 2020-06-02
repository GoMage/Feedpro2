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
 * @version      Release: 1.2.1
 * @since        Class available since Release 1.3.0
 */

namespace GoMage\Feed\Model\Mapper\Custom;

use Magento\Framework\App\ResourceConnection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class Price extends Attribute
{
    /**
     * @var mixed
     */
    private $currencyCode;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * Price constructor.
     * @param $value
     * @param $type
     * @param ResourceConnection $resource
     * @param CollectionFactory $productCollectionFactory
     * @param $additionalData
     * @param PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        $value,
        $type,
        ResourceConnection $resource,
        CollectionFactory $productCollectionFactory,
        $additionalData,
        PriceCurrencyInterface $priceCurrency
    )
    {
        parent::__construct($value, $type, $resource, $productCollectionFactory);
        $this->currencyCode = $additionalData['currencyCode'];
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @param DataObject $object
     * @return bool|float|mixed
     */
    public function map(DataObject $object)
    {
        $price = $this->priceCurrency->convert(parent::map($object), null, $this->currencyCode);
        return $price;
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    public static function getLabel()
    {
        return __('Price');
    }

    /**
     * @return array|string[]
     */
    public function getUsedAttributes()
    {
        return ['price'];
    }
}

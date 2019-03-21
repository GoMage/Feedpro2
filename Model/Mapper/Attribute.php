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
     * @var \GoMage\Feed\Model\Feed
     */
    protected $feedModel;

    /**
     * @var \GoMage\Feed\Model\ResourceModel\Feed
     */
    protected $feedResourceModel;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * Attribute constructor.
     * @param $value
     * @param \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository
     * @param \GoMage\Feed\Model\Feed $feedModel
     * @param \GoMage\Feed\Model\ResourceModel\Feed $feedResourceModel
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function __construct(
        $value,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository,
        \GoMage\Feed\Model\Feed $feedModel,
        \GoMage\Feed\Model\ResourceModel\Feed $feedResourceModel,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
    ) {
        $this->_code      = $value;
        $this->_attribute = $attributeRepository->get($this->_code);
        $this->feedModel = $feedModel;
        $this->feedResourceModel = $feedResourceModel;
        $this->request = $request;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return float|mixed|string
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        if ($this->_attribute->getFrontendModel() == 'Magento\Catalog\Model\Product\Attribute\Frontend\Image') {
            $value = $this->_attribute->getFrontend()->getUrl($object);
        } elseif ($this->_code == 'price' || $this->_code == 'special_price') {
            $feedId = $this->request->getParam('id');
            $this->feedResourceModel->load($this->feedModel, $feedId);
            $currencyCode = $this->feedModel->getCurrencyCode();
            $value = $this->priceCurrency->convert($this->_attribute->getFrontend()->getValue($object), null, $currencyCode);
            $value = number_format($value, 4, '.', '');
        } else {
            $value = $this->_attribute->getFrontend()->getValue($object);
        }
        return $value;
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return [$this->_code];
    }
}

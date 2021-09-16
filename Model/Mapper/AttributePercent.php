<?php

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
 * @version      Release: 1.4.2
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Mapper;

class AttributePercent extends Attribute implements MapperInterface
{
    /**
     * @var float
     */
    protected $_percent;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * AttributePercent constructor.
     * @param $value
     * @param \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Psr\Log\LoggerInterface $logger
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function __construct(
        $value,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository,
        \Magento\Framework\App\ResourceConnection $resource,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_percent = floatval($value['percent']);
        parent::__construct($value['code'], $attributeRepository, $resource, $logger);
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

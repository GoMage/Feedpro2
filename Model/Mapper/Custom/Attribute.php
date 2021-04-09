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
 * @version      Release: 1.4.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Mapper\Custom;

use GoMage\Feed\Model\Config\Source\Field\TypeInterface;

class Attribute implements CustomMapperInterface
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $_connection;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var string
     */
    protected $_type;

    /**
     * @var string
     */
    protected $_code;

    /**
     * Attribute constructor.
     * @param $value
     * @param $type
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    public function __construct(
        $value,
        $type,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory

    )
    {
        $this->_code = $value;
        $this->_type = $type;
        $this->_resource = $resource;
        $this->_connection = $resource->getConnection();
        $this->_productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return mixed
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        $value = false;
        switch ($this->_type) {
            case TypeInterface::ATTRIBUTE:
                $value = $object->{$this->getMethod()}();
                break;
            case TypeInterface::PARENT_ATTRIBUTE:
                $value = $this->getParentValue($object);
                break;
            case TypeInterface::EMPTY_PARENT_ATTRIBUTE:
                $value = $this->getChildIfParentValueEmpty($object);
                break;
            case TypeInterface::EMPTY_CHILD_ATTRIBUTE:
                $value = $this->getParentIfChildValueEmpty($object);
                break;
        }
        return $value;
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return [];
    }

    /**
     * @return string
     */
    public static function getLabel()
    {
        return '';
    }

    /**
     * @param $object
     * @return bool
     */
    protected function getParentValue($object)
    {
        $parent = $this->_getParentProduct($object);
        if ($parent) {
            return $parent->{$this->getMethod()}();
        }
        return false;
    }

    /**
     * @param $object
     * @return mixed
     */
    protected function getChildIfParentValueEmpty($object){
        $value = $object->{$this->getMethod()}();
        if (empty($value)) {
            $value = $this->_getChildProduct($object)->{$this->getMethod()}();
        }
        return $value;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return mixed
     */
    protected function getParentIfChildValueEmpty(\Magento\Framework\DataObject $object)
    {
        $value = $object->{$this->getMethod()}();
        if (empty($value)) {
            $value = $this->_getParentProduct($object)->{$this->getMethod()}();
        }
        return $value;
    }


    /**
     * @param \Magento\Framework\DataObject $object
     * @return bool|\Magento\Framework\DataObject
     */
    protected function _getParentProduct(\Magento\Framework\DataObject $object)
    {
        $attributes = !empty($this->getUsedAttributes()) ? $this->getUsedAttributes() : $this->_code;
        $parentId = $this->_connection
            ->select()
            ->from($this->_resource->getTableName('catalog_product_relation'), 'parent_id')
            ->where('child_id = ?', $object->getId())
            ->where('parent_id != ?', $object->getId())
            ->query()
            ->fetchColumn();
        if ($parentId) {
            $collection = $this->_productCollectionFactory->create();
            return $collection->addAttributeToSelect($attributes)
                ->addIdFilter($parentId)
                ->getFirstItem();
        }
        return false;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return bool|\Magento\Framework\DataObject
     */
    protected function _getChildProduct(\Magento\Framework\DataObject $object)
    {
        $childId = $this->_connection
            ->select()
            ->from($this->_resource->getTableName('catalog_product_relation'), 'child_id')
            ->where('parent_id = ?', $object->getId())
            ->where('child_id != ?', $object->getId())
            ->query()
            ->fetchColumn();

        if ($childId) {
            $collection = $this->_productCollectionFactory->create();
            return $collection->addAttributeToSelect($this->_code)
                ->addIdFilter($childId)
                ->fetchItem();
        }
        return false;
    }

    /**
     * @return string
     */
    private function getMethod(){
        return 'get'. str_replace(' ','',ucwords(str_replace('_',' ',$this->_code)));
    }
}

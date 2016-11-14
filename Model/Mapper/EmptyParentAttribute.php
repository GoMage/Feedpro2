<?php
namespace GoMage\Feed\Model\Mapper;

class EmptyParentAttribute extends Attribute implements MapperInterface
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


    public function __construct(
        $value,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
        parent::__construct($value, $attributeRepository);
        $this->_resource                 = $resource;
        $this->_connection               = $resource->getConnection();
        $this->_productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @param  \Magento\Framework\DataObject $object
     * @return mixed
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        $result = parent::map($object);
        if (!empty($result)) {
            return $result;
        }
        $childProduct = $this->_getChildProduct($object);
        if ($childProduct) {
            return parent::map($childProduct);
        }
        return '';
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

}
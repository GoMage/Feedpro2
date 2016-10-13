<?php
namespace GoMage\Feed\Model\Mapper\Attribute;

use GoMage\Feed\Model\Mapper\MapperInterface;


class SmallImage implements MapperInterface
{

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
    }

    /**
     * @param  $object
     * @return mixed
     */
    public function map($object)
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $object->getImage();
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return ['image'];
    }

}
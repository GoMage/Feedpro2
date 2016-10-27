<?php
namespace GoMage\Feed\Model\Mapper\Attribute;

use GoMage\Feed\Model\Mapper\MapperInterface;

class ProductUrl implements MapperInterface
{

    /**
     * @param  \Magento\Framework\DataObject $object
     * @return mixed
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        return $object->getProductUrl();
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return [];
    }

}
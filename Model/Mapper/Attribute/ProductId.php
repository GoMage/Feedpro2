<?php
namespace GoMage\Feed\Model\Mapper\Attribute;

use GoMage\Feed\Model\Mapper\MapperInterface;


class ProductId implements MapperInterface
{

    /**
     * @param  \Magento\Framework\DataObject $object
     * @return mixed
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        return $object->getId();
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return [];
    }
    
}
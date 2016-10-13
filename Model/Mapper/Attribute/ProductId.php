<?php
namespace GoMage\Feed\Model\Mapper\Attribute;

use GoMage\Feed\Model\Mapper\MapperInterface;


class ProductId implements MapperInterface
{

    /**
     * @param  $object
     * @return mixed
     */
    public function map($object)
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
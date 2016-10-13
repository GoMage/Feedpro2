<?php
namespace GoMage\Feed\Model\Mapper\Attribute;

use GoMage\Feed\Model\Mapper\MapperInterface;


class UrlKey implements MapperInterface
{

    /**
     * @param  $object
     * @return mixed
     */
    public function map($object)
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
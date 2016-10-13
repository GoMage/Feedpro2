<?php
namespace GoMage\Feed\Model\Mapper\Attribute;

use GoMage\Feed\Model\Mapper\MapperInterface;


class FreeShipping implements MapperInterface
{

    /**
     * @param  $object
     * @return mixed
     */
    public function map($object)
    {
        return $object->getWeight() ? '' : 'US:::0.00 USD';
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return ['weight'];
    }

}
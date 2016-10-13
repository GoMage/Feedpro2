<?php
namespace GoMage\Feed\Model\Mapper\Attribute;

use GoMage\Feed\Model\Mapper\MapperInterface;


class Weight implements MapperInterface
{

    /**
     * @param  $object
     * @return mixed
     */
    public function map($object)
    {
        return $object->getData('free_shipping') ? 0 : $object->getWeight();
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return ['weight', 'free_shipping'];
    }

}
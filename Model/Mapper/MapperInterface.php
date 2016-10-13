<?php
namespace GoMage\Feed\Model\Mapper;


interface MapperInterface
{
    /**
     * @param  $object
     * @return mixed
     */
    public function map($object);

    /**
     * @return array
     */
    public function getUsedAttributes();

}
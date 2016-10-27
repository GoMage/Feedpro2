<?php
namespace GoMage\Feed\Model\Mapper;


interface MapperInterface
{
    /**
     * @param  \Magento\Framework\DataObject $object
     * @return mixed
     */
    public function map(\Magento\Framework\DataObject $object);

    /**
     * @return array
     */
    public function getUsedAttributes();

}
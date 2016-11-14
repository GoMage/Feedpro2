<?php
namespace GoMage\Feed\Model\Mapper\Custom;


interface CustomMapperInterface extends \GoMage\Feed\Model\Mapper\MapperInterface
{
    /**
     * @return string
     */
    public static function getLabel();

}
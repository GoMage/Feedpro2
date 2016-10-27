<?php
namespace GoMage\Feed\Model;

interface AttributeInterface
{
    const PREFIX = 'custom:';

    /**
     * @return string
     */
    public function getAttributeCode();

}
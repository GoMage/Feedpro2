<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2021 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.4.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Mapper\Custom;
use GoMage\Feed\Model\Config\Source\Field\TypeInterface;
class ProductUrl extends Attribute implements CustomMapperInterface
{
    /**
     * @param \Magento\Framework\DataObject $object
     * @return mixed
     */
    public function map(\Magento\Framework\DataObject $object)
    {
       return parent::map($object);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        return [];
    }

    /**
     * @return string
     */
    public static function getLabel()
    {
        return __('Product Url');
    }
}

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
 * @version      Release: 1.4.2
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Mapper\Custom;

use Magento\Framework\DataObject;

class CategoryIds implements CustomMapperInterface
{
    /**
     * @inheritdoc
     */
    public function map(DataObject $object)
    {
        $categoryIds = $object->getCategoryIds();

        if (count($categoryIds)) {
            $categoryId = max($object->getCategoryIds());
        } else {
            $categoryId = '';
        }

        return $categoryId;
    }

    /**
     * @inheritdoc
     */
    public function getUsedAttributes()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getLabel()
    {
        return __('Category Ids');
    }
}

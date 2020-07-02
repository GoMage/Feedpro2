<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.3.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Config\Source;

use Magento\Catalog\Model\Product\Visibility as ProductVisibility;
use Magento\Framework\Option\ArrayInterface;

class Visibility implements ArrayInterface
{
    const CATALOG_SEARCH = 0;
    const NOT_USE = 1;
    const NOT_VISIBLE = 2;
    const CATALOG = 3;
    const SEARCH = 4;
    const ONLY_CATALOG = 5;
    const ONLY_SEARCH = 6;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::CATALOG_SEARCH, 'label' => __('Catalog, Search')],
            ['value' => self::NOT_USE, 'label' => __('Not Use Option')],
            ['value' => self::NOT_VISIBLE, 'label' => __('Not Visible Individually')],
            ['value' => self::CATALOG, 'label' => __('Catalog')],
            ['value' => self::SEARCH, 'label' => __('Search')],
            ['value' => self::ONLY_CATALOG, 'label' => __('Only Catalog')],
            ['value' => self::ONLY_SEARCH, 'label' => __('Only Search')],
        ];
    }

    /**
     * @param  int $visibility
     * @return array
     */
    public function getProductVisibility($visibility)
    {
        switch ($visibility) {
            case self::CATALOG_SEARCH:
                $visibility = [
                    ProductVisibility::VISIBILITY_IN_SEARCH,
                    ProductVisibility::VISIBILITY_IN_CATALOG,
                    ProductVisibility::VISIBILITY_BOTH
                ];
                break;
            case self::NOT_VISIBLE:
                $visibility = [ProductVisibility::VISIBILITY_NOT_VISIBLE];
                break;
            case self::CATALOG:
                $visibility = [ProductVisibility::VISIBILITY_IN_CATALOG, ProductVisibility::VISIBILITY_BOTH];
                break;
            case self::SEARCH:
                $visibility = [ProductVisibility::VISIBILITY_IN_SEARCH, ProductVisibility::VISIBILITY_BOTH];
                break;
            case self::ONLY_CATALOG:
                $visibility = [ProductVisibility::VISIBILITY_IN_CATALOG];
                break;
            case self::ONLY_SEARCH:
                $visibility = [ProductVisibility::VISIBILITY_IN_SEARCH];
                break;
        }

        return $visibility;
    }
}

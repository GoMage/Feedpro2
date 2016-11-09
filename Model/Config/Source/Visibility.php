<?php

namespace GoMage\Feed\Model\Config\Source;

use Magento\Catalog\Model\Product\Visibility as ProductVisibility;

class Visibility implements \Magento\Framework\Option\ArrayInterface
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
                return [ProductVisibility::VISIBILITY_IN_SEARCH, ProductVisibility::VISIBILITY_IN_CATALOG, ProductVisibility::VISIBILITY_BOTH];
                break;
            case self::NOT_VISIBLE:
                return [ProductVisibility::VISIBILITY_NOT_VISIBLE];
                break;
            case self::CATALOG:
                return [ProductVisibility::VISIBILITY_IN_CATALOG, ProductVisibility::VISIBILITY_BOTH];
                break;
            case self::SEARCH:
                return [ProductVisibility::VISIBILITY_IN_SEARCH, ProductVisibility::VISIBILITY_BOTH];
                break;
            case self::ONLY_CATALOG:
                return [ProductVisibility::VISIBILITY_IN_CATALOG];
                break;
            case self::ONLY_SEARCH:
                return [ProductVisibility::VISIBILITY_IN_SEARCH];
                break;
        }
        return [];
    }
}


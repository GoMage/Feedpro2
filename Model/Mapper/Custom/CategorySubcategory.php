<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.2.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Mapper\Custom;

use Magento\Catalog\Api\CategoryRepositoryInterface;

class CategorySubcategory implements CustomMapperInterface
{

    /**
     * @var string
     */
    protected $_code;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $_categoryRepository;


    public function __construct(
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->_categoryRepository = $categoryRepository;
    }

    /**
     * @param  \Magento\Framework\DataObject $object
     * @return mixed
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        $categoryIds = $object->getCategoryIds();
        if (count($categoryIds)) {
            $categoryId = max($categoryIds);
            $category   = $this->_categoryRepository->get($categoryId);
            $categories = $category->getParentCategories();
            return implode(' > ', array_map(function ($category) {
                    return $category->getName();
                }, $categories
                )
            );
        }
        return '';
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
        return __('Category > SubCategory');
    }

}
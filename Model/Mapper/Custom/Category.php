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
 * @version      Release: 1.1.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Mapper\Custom;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\DataObject;

class Category implements CustomMapperInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @inheritdoc
     */
    public function map(DataObject $object)
    {
        $categoryIds = $object->getCategoryIds();
        $categoryName = '';

        if (count($categoryIds)) {
            $categoryId = max($object->getCategoryIds());
            $category = $this->categoryRepository->get($categoryId);
            $categoryName = $category->getName();
        }

        return $categoryName;
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
        return __('Categories');
    }
}

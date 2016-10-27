<?php
namespace GoMage\Feed\Model\Mapper\Attribute;

use GoMage\Feed\Model\Mapper\MapperInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;

class CategorySubcategory implements MapperInterface
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
}
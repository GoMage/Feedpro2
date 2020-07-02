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

namespace GoMage\Feed\Model\Mapper\Custom;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\DataObject;
use Magento\Framework\App\ResourceConnection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use GoMage\Feed\Model\Config\Source\Field\TypeInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Category extends Attribute implements CustomMapperInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * Category constructor.
     * @param $value
     * @param $type
     * @param ResourceConnection $resource
     * @param CollectionFactory $productCollectionFactory
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        $value,
        $type,
        ResourceConnection $resource,
        CollectionFactory $productCollectionFactory,
        CategoryRepositoryInterface $categoryRepository
    )
    {
        parent::__construct($value, $type, $resource, $productCollectionFactory);
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @inheritdoc
     */
    public function map(DataObject $object)
    {
        $value = false;
        switch ($this->_type) {
            case TypeInterface::ATTRIBUTE:
                $value = $this->getCategoryName($object);
                break;
            case TypeInterface::PARENT_ATTRIBUTE:
                $parent = $this->_getParentProduct($object);
                $value = $parent ? $this->getCategoryName($parent) : false;
                break;
            case TypeInterface::EMPTY_PARENT_ATTRIBUTE:
                // TO DO
                //$value = $this->getChildIfParentValueEmpty($object);
                //break;
            case TypeInterface::EMPTY_CHILD_ATTRIBUTE:
                // TO DO
                //$value = $this->getParentIfChildValueEmpty($object);
                //break;
        }
        return $value;
    }

    /**
     * @param $object
     * @return string|null
     */
    protected function getCategoryName($object)
    {
        $categoryIds = $object->getCategoryIds();
        $categoryName = '';

        if (count($categoryIds)) {
            $categoryId = max($categoryIds);
            try {
                $category = $this->categoryRepository->get($categoryId);
            } catch (NoSuchEntityException $e) {
            }
            $categoryName = $category->getName();
        }

        return $categoryName;
    }

    /**
     * @inheritdoc
     */
    public function getUsedAttributes()
    {
        return ['category_ids'];
    }

    /**
     * @inheritdoc
     */
    public static function getLabel()
    {
        return __('Categories');
    }
}

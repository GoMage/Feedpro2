<?php

declare(strict_types=1);

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

namespace GoMage\Feed\Model;

use GoMage\Feed\Model\Rule\Condition\Product;

/**
 * Class Feed
 *
 * @method string getCurrencyCode()
 * @method string getContent()
 * @method string getType()
 * @method int getLimit()
 * @method int getStatus()
 * @method int getStoreId()
 * @method int getVisibility()
 * @method int getIsOutOfStock()
 * @method int getIsDisabled()
 */
class Feed extends \Magento\Rule\Model\AbstractModel
{
    const NOT_ATTRIBUTE_CODE = 'attribute_set_id';
    /**
     * @var Rule\Condition\CombineFactory
     */
    protected $_combineFactory;

    /**
     * @var \Magento\CatalogRule\Model\Rule\Action\CollectionFactory
     */
    protected $_actionCollectionFactory;

    /**
     * This class is added in Magento 2.2.
     *
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $serializer;

    /**
     * @var \Magento\Catalog\Api\ProductAttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * Feed constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param Rule\Condition\CombineFactory $combineFactory
     * @param \Magento\CatalogRule\Model\Rule\Action\CollectionFactory $actionCollectionFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \GoMage\Feed\Model\Rule\Condition\CombineFactory $combineFactory,
        \Magento\CatalogRule\Model\Rule\Action\CollectionFactory $actionCollectionFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository,
        array $data = []
    ) {
        $this->_combineFactory          = $combineFactory;
        $this->_actionCollectionFactory = $actionCollectionFactory;
        $this->attributeRepository = $attributeRepository;

        parent::__construct(
            $context,
            $registry,
            $formFactory,
            $localeDate,
            $resource,
            $resourceCollection,
            $data
        );
    }

    protected function _construct()
    {
        parent::_construct();
        $this->_init('GoMage\Feed\Model\ResourceModel\Feed');
        $this->setIdFieldName('id');
    }


    /**
     * @return string
     */
    public function getFullFileName()
    {
        return $this->getFilename() . '.' . $this->getFileExt();
    }

    /**
     * @param  string $formName
     * @return string
     */
    public function getConditionsFieldSetId($formName = '')
    {
        return $formName . 'rule_conditions_fieldset_' . $this->getId();
    }

    /**
     * Retrieve rule combine conditions model
     *
     * @return \Magento\Rule\Model\Condition\Combine
     */
    public function getConditions()
    {
        if (empty($this->_conditions)) {
            $this->_resetConditions();
        }

        // Load rule conditions if it is applicable
        if ($this->hasConditionsSerialized()) {
            $conditions = $this->getConditionsSerialized();
            if (!empty($conditions)) {
                $conditionsOutput = $this->serializer->unserialize($conditions);
                if (is_array($conditionsOutput) && !empty($conditionsOutput)) {
                    $this->_resetConditions();
                    if (array_key_exists('conditions', $conditionsOutput)) {
                        $usedForPromoRuleAttributes = [];
                        foreach ($conditionsOutput['conditions'] as $oneCondition) {
                            $attributeCode = $oneCondition['attribute'];
                            if ($attributeCode && $attributeCode != self::NOT_ATTRIBUTE_CODE) {
                                if (!in_array($attributeCode, Product::CUSTOM_ATTRIBUTE_LIST, true)) {
                                    $attribute = $this->attributeRepository->get($attributeCode);
                                    if ($attribute->getIsUsedForPromoRules()) {
                                        $usedForPromoRuleAttributes[] = $oneCondition;
                                    }
                                } else {
                                    $usedForPromoRuleAttributes[] = $oneCondition;
                                }
                            }
                        }
                        $conditionsOutput['conditions'] = $usedForPromoRuleAttributes;
                    }
                    $this->_conditions->loadArray($conditionsOutput);
                }
            }
            $this->unsConditionsSerialized();
        }

        return $this->_conditions;
    }

    /**
     * Reset rule combine conditions
     *
     * @param null|\Magento\Rule\Model\Condition\Combine $conditions
     * @return $this
     */
    protected function _resetConditions($conditions = null)
    {
        if (null === $conditions) {
            $conditions = $this->getConditionsInstance();
        }
        $conditions->setRule($this)->setId('1')->setPrefix('conditions');
        $this->setConditions($conditions);

        return $this;
    }


    public function getConditionsInstance()
    {
        return $this->_combineFactory->create();
    }

    /**
     * Getter for rule actions collection
     *
     * @return \Magento\CatalogRule\Model\Rule\Action\Collection
     */
    public function getActionsInstance()
    {
        return $this->_actionCollectionFactory->create();
    }

    /**
     * @param  int $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->setData('status', $status)->save();
        return $this;
    }

}

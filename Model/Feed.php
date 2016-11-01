<?php
namespace GoMage\Feed\Model;

/**
 * Class Feed
 *
 * @method string getContent()
 * @method string getType()
 */
class Feed extends \Magento\Rule\Model\AbstractModel implements FeedInterface
{
    /**
     * @var \Magento\CatalogRule\Model\Rule\Condition\CombineFactory
     */
    protected $_combineFactory;

    /**
     * @var \Magento\CatalogRule\Model\Rule\Action\CollectionFactory
     */
    protected $_actionCollectionFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\CatalogRule\Model\Rule\Condition\CombineFactory $combineFactory,
        \Magento\CatalogRule\Model\Rule\Action\CollectionFactory $actionCollectionFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_combineFactory          = $combineFactory;
        $this->_actionCollectionFactory = $actionCollectionFactory;

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
                $conditions = unserialize($conditions);
                if (is_array($conditions) && !empty($conditions)) {
                    $this->_conditions->loadArray($conditions);
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

    /**
     * Getter for rule conditions collection
     *
     * @return \Magento\Rule\Model\Condition\Combine
     */
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

}
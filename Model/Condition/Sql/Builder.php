<?php
declare(strict_types=1);
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

namespace GoMage\Feed\Model\Condition\Sql;

use GoMage\Feed\Model\Rule\Condition\Product as RuleConditionProduct;
use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Rule\Model\Condition\Sql\Builder as CoreBuilder;
use Magento\Rule\Model\Condition\Sql\ExpressionFactory;
use Magento\Catalog\Api\Data\ProductAttributeInterface;

class Builder extends CoreBuilder
{
    /**
     * @var array
     */
    protected $stringConditionOperatorMap = [
        '{}' => ':field LIKE ?',
        '!{}' => ':field NOT LIKE ?',
    ];

    /**
     * @var AttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * Builder constructor.
     * @param ExpressionFactory $expressionFactory
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(ExpressionFactory $expressionFactory, AttributeRepositoryInterface $attributeRepository)
    {
        parent::__construct($expressionFactory, $attributeRepository);
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * Returns sql expression based on rule condition.
     *
     * @param AbstractCondition $condition
     * @param string $value
     * @param bool $isDefaultStoreUsed no longer used because caused an issue about not existing table alias
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getMappedSqlCondition(
        AbstractCondition $condition,
        string $value = '',
        bool $isDefaultStoreUsed = true
    ): string {
        $argument = $condition->getMappedSqlField();

        // If rule hasn't valid argument - prevent incorrect rule behavior.
        if (empty($argument)) {
            return $this->_expressionFactory->create(['expression' => '1 = -1'])->__toString();
        } elseif (preg_match('/[^a-z0-9\-_\.\`]/i', $argument) > 0) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid field'));
        }

        $conditionOperator = $condition->getOperatorForValidate();

        if (!isset($this->_conditionOperatorMap[$conditionOperator])) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Unknown condition operator'));
        }

        $defaultValue = 0;
        $attributeCode = $condition->getData('attribute');
        //operator 'contains {}' is mapped to 'IN()' query that cannot work with substrings
        // adding mapping to 'LIKE %%'
        if ($condition->getInputType() === 'string'
            && in_array($conditionOperator, array_keys($this->stringConditionOperatorMap), true)
        ) {
            $sql = str_replace(
                ':field',
                $this->_connection->getIfNullSql($this->_connection->quoteIdentifier($argument), $defaultValue),
                $this->stringConditionOperatorMap[$conditionOperator]
            );
            $bindValue = $condition->getBindArgumentValue();
            $expression = $value . $this->_connection->quoteInto($sql, "%$bindValue%");
        } elseif ($attributeCode && !$this->isAttributeSetOrCategory($attributeCode) &&
                !$this->isAttributeGlobal($attributeCode)) {
            $sql = str_replace(
                ':field',
                $this->_connection->getIfNullSql($this->_connection->quoteIdentifier($argument),
                    $this->_connection->getIfNullSql($this->_connection->quoteIdentifier(
                        'at_' . $attributeCode . '_default.value'), $defaultValue
                    )
                ),
                $this->_conditionOperatorMap[$conditionOperator]
            );
            $bindValue = $condition->getBindArgumentValue();
            $expression = $value . $this->_connection->quoteInto($sql, $bindValue);
        } else {
            $sql = str_replace(
                ':field',
                $this->_connection->getIfNullSql($this->_connection->quoteIdentifier($argument), $defaultValue),
                $this->_conditionOperatorMap[$conditionOperator]
            );
            $bindValue = $condition->getBindArgumentValue();
            $expression = $value . $this->_connection->quoteInto($sql, $bindValue);
        }
        // values for multiselect attributes can be saved in comma-separated format
        // below is a solution for matching such conditions with selected values
        if (is_array($bindValue) && \in_array($conditionOperator, ['()', '{}'], true)) {
            foreach ($bindValue as $item) {
                $expression .= $this->_connection->quoteInto(
                    " OR (FIND_IN_SET (?, {$this->_connection->quoteIdentifier($argument)}) > 0)",
                    $item
                );
            }
        }
        return $this->_expressionFactory->create(
            ['expression' => $expression]
        )->__toString();
    }

    /**
     * @param $attributeCode
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isAttributeGlobal($attributeCode)
    {
        if (in_array($attributeCode, RuleConditionProduct::CUSTOM_ATTRIBUTE_LIST)) {
            return true;
        }
        $attribute = $this->attributeRepository->get(ProductAttributeInterface::ENTITY_TYPE_CODE,
                $attributeCode);
        return $attribute->getData('is_global');
    }

    /**
     * @param $attributeCode
     * @return bool
     */
    public function isAttributeSetOrCategory($attributeCode)
    {
        return in_array($attributeCode, ['attribute_set_id', 'category_ids']);
    }
}

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

namespace GoMage\Feed\Model\Operator;

class Factory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var array
     */
    protected $_operators = [
        OperatorInterface::EQUAL         => 'GoMage\Feed\Model\Operator\Equal',
        OperatorInterface::NOT_EQUAL     => 'GoMage\Feed\Model\Operator\NotEqual',
        OperatorInterface::GREATER       => 'GoMage\Feed\Model\Operator\Greater',
        OperatorInterface::LESS          => 'GoMage\Feed\Model\Operator\Less',
        OperatorInterface::GREATER_EQUAL => 'GoMage\Feed\Model\Operator\GreaterEqual',
        OperatorInterface::LESS_EQUAL    => 'GoMage\Feed\Model\Operator\LessEqual',
        OperatorInterface::LIKE          => 'GoMage\Feed\Model\Operator\Like',
        OperatorInterface::NOT_LIKE      => 'GoMage\Feed\Model\Operator\NotLike',
    ];

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * @param  int $operator
     * @return \GoMage\Feed\Model\Operator\OperatorInterface
     */
    public function get($operator)
    {
        return $this->_objectManager->get($this->_operators[(int)$operator]);
    }

}

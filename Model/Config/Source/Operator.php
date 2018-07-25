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

namespace GoMage\Feed\Model\Config\Source;

use GoMage\Feed\Model\Operator\OperatorInterface;

class Operator implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => OperatorInterface::EQUAL, 'label' => __('equal')],
            ['value' => OperatorInterface::NOT_EQUAL, 'label' => __('not equal')],
            ['value' => OperatorInterface::GREATER, 'label' => __('greater than')],
            ['value' => OperatorInterface::LESS, 'label' => __('less than')],
            ['value' => OperatorInterface::GREATER_EQUAL, 'label' => __('greater than or equal to')],
            ['value' => OperatorInterface::LESS_EQUAL, 'label' => __('less than or equal to')],
            ['value' => OperatorInterface::LIKE, 'label' => __('like')],
            ['value' => OperatorInterface::NOT_LIKE, 'label' => __('not like')],
        ];
    }

}

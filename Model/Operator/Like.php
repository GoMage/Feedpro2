<?php

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
 * @version      Release: 1.4.1
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Operator;

class Like implements OperatorInterface
{

    /**
     * @param  $testable
     * @param  $value
     * @return bool
     */
    public function compare($testable, $value)
    {
        return (strpos($testable, $value) !== false);
    }
}

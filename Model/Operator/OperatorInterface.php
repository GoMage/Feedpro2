<?php
namespace GoMage\Feed\Model\Operator;


interface OperatorInterface
{
    const EQUAL = 0;
    const NOT_EQUAL = 1;
    const GREATER = 2;
    const LESS = 3;
    const GREATER_EQUAL = 4;
    const LESS_EQUAL = 5;
    const LIKE = 6;
    const NOT_LIKE = 7;

    /**
     * @param  $testable
     * @param  $value
     * @return bool
     */
    public function compare($testable, $value);

}
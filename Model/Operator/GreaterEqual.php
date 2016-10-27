<?php
namespace GoMage\Feed\Model\Operator;


class GreaterEqual implements OperatorInterface
{

    /**
     * @param  $testable
     * @param  $value
     * @return bool
     */
    public function compare($testable, $value)
    {
        return ($testable >= $value);
    }
}
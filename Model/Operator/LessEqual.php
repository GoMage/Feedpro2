<?php
namespace GoMage\Feed\Model\Operator;


class LessEqual implements OperatorInterface
{

    /**
     * @param  $testable
     * @param  $value
     * @return bool
     */
    public function compare($testable, $value)
    {
        return ($testable <= $value);
    }
}
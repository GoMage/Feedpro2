<?php
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
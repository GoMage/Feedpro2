<?php
namespace GoMage\Feed\Model\Output;


class Floats implements OutputInterface
{
    /**
     * @param  $value
     * @return float
     */
    public function format($value)
    {
        return number_format((float)$value, 2, '.', '');
    }
}
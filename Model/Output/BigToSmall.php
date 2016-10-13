<?php
namespace GoMage\Feed\Model\Output;


class BigToSmall implements OutputInterface
{
    /**
     * @param  $value
     * @return string
     */
    public function format($value)
    {
        return strtolower($value);
    }
}
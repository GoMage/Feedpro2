<?php
namespace GoMage\Feed\Model\Output;


class Integer implements OutputInterface
{
    /**
     * @param  $value
     * @return int
     */
    public function format($value)
    {
        return intval($value);
    }
}
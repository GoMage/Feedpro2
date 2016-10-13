<?php
namespace GoMage\Feed\Model\Output;


class Defaults implements OutputInterface
{
    /**
     * @param  $value
     * @return mixed
     */
    public function format($value)
    {
        return $value;
    }
}
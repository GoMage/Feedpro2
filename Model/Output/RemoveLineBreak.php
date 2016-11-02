<?php
namespace GoMage\Feed\Model\Output;


class RemoveLineBreak implements OutputInterface
{
    /**
     * @param  $value
     * @return string
     */
    public function format($value)
    {
        return str_replace("\n", '', str_replace("\r", '', $value));
    }
}
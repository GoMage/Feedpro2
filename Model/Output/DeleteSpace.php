<?php
namespace GoMage\Feed\Model\Output;


class DeleteSpace implements OutputInterface
{
    /**
     * @param  $value
     * @return string
     */
    public function format($value)
    {
        return str_replace(" ", "", $value);
    }
}
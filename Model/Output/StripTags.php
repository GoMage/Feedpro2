<?php
namespace GoMage\Feed\Model\Output;


class StripTags implements OutputInterface
{
    /**
     * @param  $value
     * @return string
     */
    public function format($value)
    {
        return trim(strip_tags($value));
    }
}
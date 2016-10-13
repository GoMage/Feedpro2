<?php
namespace GoMage\Feed\Model\Output;


class SpecialDecode implements OutputInterface
{
    /**
     * @param  $value
     * @return string
     */
    public function format($value)
    {
        return htmlspecialchars_decode($value);
    }
}
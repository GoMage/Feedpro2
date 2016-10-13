<?php
namespace GoMage\Feed\Model\Output;


class SpecialEncode implements OutputInterface
{
    /**
     * @param  $value
     * @return string
     */
    public function format($value)
    {
        $encoding = mb_detect_encoding($value);
        $value    = mb_convert_encoding($value, "UTF-8", $encoding);
        return htmlentities($value, ENT_QUOTES, "UTF-8");
    }
}
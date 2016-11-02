<?php
namespace GoMage\Feed\Model\Output;

interface OutputInterface
{
    const NONE = 0;
    const INTEGER = 1;
    const FLOATS = 2;
    const STRIP_TAGS = 3;
    const SPECIAL_ENCODE = 4;
    const SPECIAL_DECODE = 5;
    const DELETE_SPACE = 6;
    const BIG_TO_SMALL = 7;
    const REMOVE_LINE_BREAK = 8;

    /**
     * @param  $value
     * @return mixed
     */
    public function format($value);

}
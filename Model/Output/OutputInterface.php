<?php
namespace GoMage\Feed\Model\Output;

interface OutputInterface
{
    const NONE = '';
    const INTEGER = 'integer';
    const FLOATS = 'float';
    const STRIP_TAGS = 'strip_tags';
    const SPECIAL_ENCODE = 'special_encode';
    const SPECIAL_DECODE = 'special_decode';
    const DELETE_SPACE = 'delete_space';
    const BIG_TO_SMALL = 'big_to_small';
    const REMOVE_LINE_BREAK = 'remove_lb';

    /**
     * @param  $value
     * @return mixed
     */
    public function format($value);

}
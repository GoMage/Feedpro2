<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2021 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.4.1
 * @since        Class available since Release 1.0.0
 */

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
    const HTML_SPECIAL_CHARS_ENCODE = 'htmlspecialchars';

    /**
     * @param  $value
     * @return mixed
     */
    public function format($value);
}

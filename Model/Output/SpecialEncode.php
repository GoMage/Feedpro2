<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.3.0
 * @since        Class available since Release 1.0.0
 */

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

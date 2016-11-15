<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.0.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Config\Source\Extension;

class Csv implements \Magento\Framework\Option\ArrayInterface
{

    const CSV = 'csv';
    const TXT = 'txt';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '', 'label' => ''],
            ['value' => self::CSV, 'label' => __('CSV')],
            ['value' => self::TXT, 'label' => __('TXT')],
        ];
    }

}

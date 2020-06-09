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

namespace GoMage\Feed\Model\Config\Source;

class FeedType implements \Magento\Framework\Option\ArrayInterface
{

    const CSV_TYPE = 'csv';
    const XML_TYPE = 'xml';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::CSV_TYPE, 'label' => __('CSV')],
            ['value' => self::XML_TYPE, 'label' => __('XML')],
        ];
    }

}

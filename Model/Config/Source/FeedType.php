<?php

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

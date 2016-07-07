<?php

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

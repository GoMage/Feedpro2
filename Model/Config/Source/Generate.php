<?php

namespace GoMage\Feed\Model\Config\Source;

class Generate implements \Magento\Framework\Option\ArrayInterface
{

    const FOPEN = 0;
    const CURL = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::FOPEN, 'label' => __('Fopen')],
            ['value' => self::CURL, 'label' => __('Curl')],
        ];
    }

}

<?php

namespace GoMage\Feed\Model\Config\Source\Extension;

class Xml implements \Magento\Framework\Option\ArrayInterface
{
    const XML = 'xml';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '', 'label' => ''],
            ['value' => self::XML, 'label' => __('XML')],
        ];
    }

}

<?php

namespace GoMage\Feed\Model\Config\Source;

class Notify implements \Magento\Framework\Option\ArrayInterface
{

    const ERRORS = 0;
    const GENERATED = 1;
    const UPLOADED = 2;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::ERRORS, 'label' => __('Errors')],
            ['value' => self::GENERATED, 'label' => __('File Successfully Generated')],
            ['value' => self::UPLOADED, 'label' => __('File Successfully Uploaded')],
        ];
    }

}

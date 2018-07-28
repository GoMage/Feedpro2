<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.1.1
 * @since        Class available since Release 1.0.0
 */

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

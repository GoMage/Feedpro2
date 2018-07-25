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
 * @version      Release: 1.1.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Config\Source;

use GoMage\Feed\Model\Output\OutputInterface;

class Output implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => OutputInterface::NONE, 'label' => __('None')],
            ['value' => OutputInterface::INTEGER, 'label' => __('Integer')],
            ['value' => OutputInterface::FLOATS, 'label' => __('Float')],
            ['value' => OutputInterface::STRIP_TAGS, 'label' => __('Strip Tags')],
            ['value' => OutputInterface::SPECIAL_ENCODE, 'label' => __('Encode special chars')],
            ['value' => OutputInterface::SPECIAL_DECODE, 'label' => __('Decode special chars')],
            ['value' => OutputInterface::DELETE_SPACE, 'label' => __('Delete Space')],
            ['value' => OutputInterface::BIG_TO_SMALL, 'label' => __('Big to small')],
            ['value' => OutputInterface::REMOVE_LINE_BREAK, 'label' => __('Remove line break symbols')],
            ['value' => OutputInterface::HTML_SPECIAL_CHARS_ENCODE, 'label' => __('Encode html special chars')],
        ];
    }

}

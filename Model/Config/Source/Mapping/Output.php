<?php

namespace GoMage\Feed\Model\Config\Source\Mapping;

use GoMage\Feed\Model\Output\OutputInterface;

class Output implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => OutputInterface::DEFAULTS, 'label' => __('Default')],
            ['value' => OutputInterface::INTEGER, 'label' => __('Integer')],
            ['value' => OutputInterface::FLOATS, 'label' => __('Float')],
            ['value' => OutputInterface::STRIP_TAGS, 'label' => __('Strip Tags')],
            ['value' => OutputInterface::SPECIAL_ENCODE, 'label' => __('Encode special chars')],
            ['value' => OutputInterface::SPECIAL_DECODE, 'label' => __('Decode special chars')],
            ['value' => OutputInterface::DELETE_SPACE, 'label' => __('Delete Space')],
            ['value' => OutputInterface::BIG_TO_SMALL, 'label' => __('Big to small')],
        ];
    }

}

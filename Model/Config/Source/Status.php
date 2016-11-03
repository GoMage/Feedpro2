<?php

namespace GoMage\Feed\Model\Config\Source;

class Status implements \Magento\Framework\Option\ArrayInterface
{

    const NEWBORN = 0;
    const IN_PROGRESS = 1;
    const COMPLETED = 2;
    const FAILED = 3;
    const STOPPED = 4;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::NEWBORN, 'label' => __('New')],
            ['value' => self::IN_PROGRESS, 'label' => __('In progress')],
            ['value' => self::COMPLETED, 'label' => __('Completed')],
            ['value' => self::FAILED, 'label' => __('Failed')],
            ['value' => self::STOPPED, 'label' => __('Stopped')],
        ];
    }

}

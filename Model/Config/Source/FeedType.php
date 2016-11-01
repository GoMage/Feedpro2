<?php

namespace GoMage\Feed\Model\Config\Source;

use GoMage\Feed\Model\FeedInterface;

class FeedType implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => FeedInterface::CSV_TYPE, 'label' => __('CSV')],
            ['value' => FeedInterface::XML_TYPE, 'label' => __('XML')],
        ];
    }

}

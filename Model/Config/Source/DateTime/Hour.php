<?php

namespace GoMage\Feed\Model\Config\Source\DateTime;

class Hour implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $hours = [];
        for ($i = 0; $i < 24; $i++) {
            $hours[] = ['value' => $i, 'label' => sprintf('%02d:00', $i)];
        }
        return $hours;
    }

}

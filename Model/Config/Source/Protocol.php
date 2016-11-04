<?php

namespace GoMage\Feed\Model\Config\Source;

use GoMage\Feed\Model\Protocol\ProtocolInterface;

class Protocol implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => ProtocolInterface::FTP, 'label' => __('FTP / FTPS')],
            ['value' => ProtocolInterface::SSH, 'label' => __('SFTP (SSH)')],
        ];
    }

}

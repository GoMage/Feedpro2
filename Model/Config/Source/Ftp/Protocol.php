<?php

namespace GoMage\Feed\Model\Config\Source\Ftp;

class Protocol implements \Magento\Framework\Option\ArrayInterface
{

    const FTP = 0;
    const SSH = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::FTP, 'label' => __('FTP / FTPS')],
            ['value' => self::SSH, 'label' => __('SFTP (SSH)')],
        ];
    }

}

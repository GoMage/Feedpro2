<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2021 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.4.2
 * @since        Class available since Release 1.0.0
 */

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

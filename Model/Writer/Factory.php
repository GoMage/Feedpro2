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
 * @version      Release: 1.2.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Writer;

use GoMage\Feed\Model\Config\Source\FeedType;

class Factory
{
    protected $_writers = [
        FeedType::CSV_TYPE => 'GoMage\Feed\Model\Writer\Csv',
        FeedType::XML_TYPE => 'GoMage\Feed\Model\Writer\Xml'
    ];

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * @param string $type
     * @param array $arguments
     * @return \GoMage\Feed\Model\Writer\WriterInterface
     * @throws \Exception
     */
    public function create($type, array $arguments = [])
    {
        if (!isset($this->_writers[$type])) {
            throw new \Exception(__('Undefined writer.'));
        }
        return $this->_objectManager->create($this->_writers[$type], $arguments);
    }

}

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

namespace GoMage\Feed\Model\Content;

use GoMage\Feed\Model\Config\Source\FeedType;

class Factory
{
    protected $_contents = [
        FeedType::CSV_TYPE => 'GoMage\Feed\Model\Content\Csv',
        FeedType::XML_TYPE => 'GoMage\Feed\Model\Content\Xml'
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
     * @return \GoMage\Feed\Model\Content\ContentInterface
     * @throws \Exception
     */
    public function create($type, array $arguments = [])
    {
        if (!isset($this->_contents[$type])) {
            throw new \Exception(__('Undefined content type.'));
        }
        return $this->_objectManager->create($this->_contents[$type], $arguments);
    }

}

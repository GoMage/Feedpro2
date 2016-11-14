<?php

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
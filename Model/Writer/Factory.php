<?php

namespace GoMage\Feed\Model\Writer;

class Factory
{
    /**
     * Object Manager
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * @param string $className
     * @param array $arguments
     * @return \GoMage\Feed\Model\Writer\WriterInterface
     */
    public function create($className, array $arguments = [])
    {
        return $this->_objectManager->create($className, $arguments);
    }

}

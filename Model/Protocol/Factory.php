<?php

namespace GoMage\Feed\Model\Protocol;

class Factory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var array
     */
    protected $_protocols = [
        ProtocolInterface::FTP => 'GoMage\Feed\Model\Protocol\Ftp',
        ProtocolInterface::SSH => 'GoMage\Feed\Model\Protocol\Ssh',
    ];

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * @param  int $protocol
     * @param  array $data
     * @return \GoMage\Feed\Model\Protocol\ProtocolInterface
     */
    public function create($protocol, $data = [])
    {
        /** @var \GoMage\Feed\Model\Protocol\Params $params */
        $params = $this->_objectManager->create('GoMage\Feed\Model\Protocol\Params', ['data' => $data]);
        return $this->_objectManager->create($this->_protocols[intval($protocol)], ['params' => $params]);
    }

}

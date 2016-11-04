<?php

namespace GoMage\Feed\Model\Protocol;

abstract class AbstractProtocol implements ProtocolInterface
{
    /**
     * @var \GoMage\Feed\Model\Protocol\Params
     */
    protected $_params;

    /**
     * @var resource
     */
    protected $_connection;


    public function __construct(
        \GoMage\Feed\Model\Protocol\Params $params
    ) {
        $this->_params = $params;
        $this->_connect();
    }

    /**
     * @return resource
     */
    abstract protected function _connect();
}
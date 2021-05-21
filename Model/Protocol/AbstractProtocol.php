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
 * @version      Release: 1.4.1
 * @since        Class available since Release 1.0.0
 */

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

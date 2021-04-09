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
 * @version      Release: 1.4.0
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Feed\Model\Protocol;

class Ssh extends AbstractProtocol
{

    /**
     * @throws \Exception
     */
    protected function _connect()
    {
        if (!extension_loaded('ssh2')) {
            throw new \Exception('SSH2 extension is not loaded.');
        }

        $this->_connection = ssh2_connect($this->_params->getHost(), $this->_params->getPort());
        if (!$this->_connection) {
            throw new \Exception('Invalid SFTP/SSH access (Host Name or Port).');
        }
        if (!ssh2_auth_password($this->_connection, $this->_params->getUser(), $this->_params->getPassword())) {
            throw new \Exception('Invalid SFTP/SSH access (User Name or Password).');
        }
    }

    /**
     * @param  string $source
     * @param  string $dest
     * @return bool
     */
    public function execute($source, $dest)
    {
        $remoteFile = rtrim($dest, '/') . '/' . basename($source);
        return ssh2_scp_send($this->_connection, $source, $remoteFile);
    }
}

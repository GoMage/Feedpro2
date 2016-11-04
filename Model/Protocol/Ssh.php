<?php

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
        ssh2_auth_password($this->_connection, $this->_params->getUser(), $this->_params->getPassword());
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
<?php

namespace GoMage\Feed\Model\Protocol;

class Ftp extends AbstractProtocol
{

    /**
     * @throws \Exception
     */
    protected function _connect()
    {
        if (!extension_loaded('ftp')) {
            throw new \Exception('FTP extension is not loaded.');
        }

        $this->_connection = ftp_connect($this->_params->getHost(), $this->_params->getPort());
        ftp_login($this->_connection, $this->_params->getUser(), $this->_params->getPassword());
        ftp_pasv($this->_connection, $this->_params->getPassiveMode());
    }

    /**
     * @param  string $source
     * @param  string $dest
     * @return bool
     */
    public function execute($source, $dest)
    {
        ftp_chdir($this->_connection, $dest);
        return ftp_put($this->_connection, basename($source), $source, FTP_BINARY);
    }
}
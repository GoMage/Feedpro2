<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.3.2
 * @since        Class available since Release 1.0.0
 */

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
        if (!$this->_connection) {
            throw new \Exception('Invalid FTP/FTPS access (Host Name or Port).');
        }
        if (!ftp_login($this->_connection, $this->_params->getUser(), $this->_params->getPassword())) {
            throw new \Exception('Invalid FTP/FTPS access (User Name or Password).');
        }
        if (!ftp_pasv($this->_connection, $this->_params->getPassiveMode())) {
            throw new \Exception('Invalid FTP/FTPS access (Passive/Active Mode).');
        }
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

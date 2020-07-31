<?php

namespace GoMage\Feed\Controller\Adminhtml\Ajax;

class Ftp extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\CsrfAwareActionInterface
{
    /**
     * Ftp_host constant used for name replace in hostname
     */
    const FTPHOSTPATTERN = '/ftp./';

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * Ftp constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if ($this->getRequest()->isAjax() && $this->getRequest()->getParam('isFtp')) {
            $params = [];
            $params['ftp_protocol'] = $this->getRequest()->getParam('ftpProtocol');
            $params['host'] = $this->replaceFtpHostname($this->getRequest()->getParam('ftpHost'));
            $params['port'] = $this->getRequest()->getParam('ftpPort');
            $params['user'] = $this->getRequest()->getParam('ftpUser');
            $params['password'] = $this->getRequest()->getParam('ftpPassword');
            $params['passive_mode'] = $this->getRequest()->getParam('ftpPassiveMode');

            $exeption = false;
            if (!$params['ftp_protocol']) {
                $exeption = $this->getFtpConnect($params, $exeption);
            } else {
                $exeption = $this->getSshConnect($params, $exeption);
            }
            $resultJson = $this->resultJsonFactory->create();
            if ($exeption) {
                return $resultJson->setData(['error' => true, 'value' => $exeption]);
            } else {
                return $resultJson->setData(['success' => true, 'value' => $params]);
            }
        }
    }

    /**
     * @param $params
     * @param $exeption
     * @return string
     */
    public function getFtpConnect($params, $exeption)
    {
        if (!extension_loaded('ftp')) {
            return $exeption = 'FTP extension is not loaded.';
        }
        try {
            $connection = ftp_connect($params['host'], $params['port']);
            if (!$connection) {
                return $exeption = 'Invalid FTP/FTPS access (Host Name or Port).';
            }
        } catch (\Exception $e) {
            return $exeption = 'Invalid FTP/FTPS access (Host Name or Port).';
        }
        try {
            ftp_login($connection, $params['user'], $params['password']);
        } catch (\Exception $e) {
            return $exeption = 'Invalid FTP/FTPS access (User Name or Password).';
        }
        try {
            ftp_pasv($connection, $params['passive_mode']);
        } catch (\Exception $e) {
            return $exeption = 'Invalid FTP/FTPS access (Passive/Active Mode).';
        }
        return $exeption;
    }

    /**
     * @param $params
     * @param $exeption
     * @return string
     */
    public function getSshConnect($params, $exeption)
    {

        if (!extension_loaded('ssh2')) {
            return $exeption = 'SSH2 extension is not loaded.';
        }
        try {
            ssh2_connect($params['host'], $params['port']);
        } catch (\Exception $e) {
            return $exeption = 'Invalid SFTP/SSH access (Host Name or Port).';
        }
        try {
            $connection = ssh2_connect($params['host'], $params['port']);
            ssh2_auth_password($connection, $params['user'], $params['password']);
        } catch (\Exception $e) {
            return $exeption = 'Invalid SFTP/SSH access (Host Name or Port).';
        }
        return $exeption;
    }

    /**
     * @param $dbhostname
     * @return string|string[]|null
     */
    public function replaceFtpHostname($dbhostname)
    {
        return preg_replace(self::FTPHOSTPATTERN, '', $dbhostname);
    }

    /**
     * @inheritDoc
     */
    public function createCsrfValidationException(
        \Magento\Framework\App\RequestInterface $request
    ): ?\Magento\Framework\App\Request\InvalidRequestException
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function validateForCsrf(\Magento\Framework\App\RequestInterface $request): ?bool
    {
        return true;
    }

}

